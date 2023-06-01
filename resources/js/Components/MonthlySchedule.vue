<script setup>
import { ref, onMounted, onUnmounted, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import chroma from "chroma-js";
import * as d3 from "d3";
import pkg from "lodash";
import dayjs from "dayjs";
import Modal from "./Modal.vue";
const { debounce, chunk, sortedIndexBy, sortedIndex } = pkg;
const props = defineProps({
    monthData: Object,
});

const loginModal = ref(false);

const timelineWrapper = ref();

// TimelineData is displayed in d3.js against the TimelineWrapper.
// Display props.monthData.title in text on Y-axis
// X-axis displays a tick for each day from today to the maximum number of days in props.monthData and is fixed at the top
// The length of the tick is equal to the height.
// Default display range is 2 weeks with zoom and horizontal panning.
// The height of each event is 20, and if it exceeds the height, it can be displayed by vertical panning. In this case, X-axis label is fixed at the top.
// Display width is reflected by obtaining the width of the timelineWrapper
// When resizing is detected, elements related to width are updated.
// background color is #2d2a2d, text color is #fff and tick color is #fff

// setting
let width = 1000;
let margin = {
    top: 50,
    bottom: 40,
    left: 100,
    right: 20,
};
let marginLeftSm = 30;
let marginLeftLg = 100;
const events = props.monthData;
const eventHeight = 30;
const eventPadding = 1;
const height =
    (eventHeight + eventPadding) * events.length +
    margin.top +
    margin.bottom +
    eventPadding;

const cols = chroma
    .scale(["#c20063", "#D8346E", "#ff99cd"])
    .classes(props.monthData.length)
    .mode("lch");

// elements
let field = null;
let graphArea = null;
let clipArea = null;
let clipPath = null;
let mainArea = null;
let eventArea = null;
let event = null;
let eventBar = null;
let eventLabel = null;
let eventLabelBg = null;
let eventLabelText = null;
let instrumentArea = null;
let instrumentLabel = null;
let instrumentLabelBg = null;
let dummyInstrumentBtn = null;
let instrumentLabelRight = null;

let zoom = null;

let maxDay = props.monthData[Object.keys(props.monthData).length - 1].end_date;
let today = new Date();
today.setHours(0, 0, 0, 0);
let defScale = 1;
let defScaleWidth = 1000;

// axis
let xScale = null;
let yScale = null;
let xAxis = null;
let yAxis = null;

let currentPosition = 0;
let currentEventIndex = 0;

const formatDate = (date) => {
    if (width < 500) {
        return dayjs(date).format("DD");
    } else {
        return dayjs(date).format("MM.DD");
    }
};
const formatDateFull = (date) => {
    return dayjs(date).format("MM.DD");
};

const changeWidth = () => {
    width = timelineWrapper.value.clientWidth;
    console.log(width);
    if (width < 500) {
        margin.left = marginLeftSm;
    } else {
        margin.left = marginLeftLg;
    }
    graphArea.attr("width", width);
    xScale.range([0, width - margin.left - margin.right]);
    xAxis.scale(xScale);
    mainArea.attr(
        "transform",
        "translate(" + margin.left + "," + margin.top + ")"
    );
    clipPath
        .attr("width", width)
        .attr("height", height)
        .attr("x", margin.left)
        .attr("y", 0);
    setAxis();
    updateDrawAxis();
    updateDrawEvent();
    updateDrawInstrumentLabel();
    zoom = d3
        .zoom()
        .scaleExtent([1, 1])
        .translateExtent([
            [-margin.left, 0],
            [defScaleWidth + margin.right, 0],
        ])
        .on("zoom", zoomed);
    graphArea.call(zoom);
};

// let oneWeekDay = new Date();
// oneWeekDay.setDate(oneWeekDay.getDate() + 7);
// oneWeekDay.setHours(0, 0, 0, 0);
// let twoWeekDay = new Date();
// twoWeekDay.setDate(twoWeekDay.getDate() + 14);
// twoWeekDay.setHours(0, 0, 0, 0);

const setAxis = () => {
    let long = (new Date(maxDay) - new Date(today)) / 86400000;
    if (width < 500) {
        if (long < 7) {
            defScale = 7;
        } else {
            defScale = long / 7;
        }
    } else {
        if (long < 14) {
            defScale = 14;
        } else {
            defScale = long / 14;
        }
    }

    defScaleWidth = (width - margin.left - margin.right) * defScale;
    xScale = d3
        .scaleTime()
        .domain([new Date(today), new Date(maxDay)])
        .range([0, defScaleWidth]);
    yScale = d3
        .scaleLinear()
        .domain([0, props.monthData.max_day])
        .range([height - margin.top - margin.bottom, 0]);
    xAxis = d3
        .axisTop(xScale)
        .tickSize(-height + margin.top + margin.bottom)
        .ticks(d3.timeDay.every(1))
        .tickFormat((d, i) => {
            if (i === 0) {
                return formatDateFull(d);
            } else {
                return formatDate(d);
            }
        });
};
const drawAxis = () => {
    mainArea
        .append("g")
        .attr("class", "x-axis")
        .call(xAxis)
        .attr("stroke", "#fff")
        .selectAll("line")
        .attr("stroke", "#fff")
        .attr("stroke-width", 0.3);
    mainArea
        .select(".x-axis")
        .selectAll("text")
        .attr("fill", "#fff")
        .attr("font-size", width < 500 ? 8 : 10)
        .attr("text-anchor", "start")
        .attr("stroke-width", 0.1);
};
const updateDrawAxis = () => {
    mainArea
        .select(".x-axis")
        .call(xAxis)
        .attr("stroke", "#fff")
        .selectAll("line")
        .attr("stroke", "#fff")
        .attr("stroke-width", 0.3);
    mainArea
        .select(".x-axis")
        .selectAll("text")
        .attr("fill", "#fff")
        .attr("font-size", width < 500 ? 8 : 10)
        .attr("text-anchor", "start")
        .attr("stroke-width", 0.1);
};

const drawInstrumentLabel = () => {
    // draw dummy instrument label
    instrumentArea = graphArea
        .append("g")
        .attr("transform", "translate(" + 0 + "," + margin.top + ")")
        .attr("class", "instrument-area");
    instrumentLabel = instrumentArea
        .selectAll("g")
        .data(props.monthData)
        .enter()
        .append("g")
        .attr("class", "instrument-label");
    instrumentLabel
        .append("rect")
        .attr("x", 0)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", 6)
        .attr("height", eventHeight)
        .attr("fill", (d, i) => {
            return cols(i / props.monthData.length);
        });
    instrumentLabelBg = instrumentLabel
        .append("rect")
        .attr("x", 0)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", margin.left - 2)
        .attr("height", eventHeight)
        .attr("class", "instrument-label-bg")
        .attr("fill", "#fff")
        .attr("opacity", 0.2);
    instrumentLabelRight = instrumentLabel
        .append("rect")
        .attr("x", margin.left - 6)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", 4)
        .attr("height", eventHeight)
        .attr("fill", "#2d2a2d")
        .attr("opacity", 0.5);
    instrumentLabel
        .append("text")
        .attr("x", 10)
        .attr("y", (d, i) => {
            return (
                (eventHeight + eventPadding) * i +
                eventPadding +
                eventHeight / 2
            );
        })
        .attr("fill", "#fff")
        .attr("font-size", 10)
        .attr("text-anchor", "start")
        .attr("stroke-width", 0.1)
        .text((d, i) => {
            return i + 1;
        });
    dummyInstrumentBtn = instrumentLabel
        .append("g")
        .attr("class", "dummy-instrument-btn");
    // draw M button
    dummyInstrumentBtn
        .append("g")
        .attr("class", "dummy-M")
        .append("rect")
        .attr("x", 25)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2;
        })
        .attr("width", eventHeight - 15)
        .attr("height", eventHeight - 15)
        .attr("fill", (d, i) => {
            if (new Date(d.start_date) < new Date(today)) {
                return "#fff";
            } else {
                return "#D8346E";
            }
        })
        .attr("stroke", "#2d2a2d")
        .attr("opacity", (d, i) => {
            if (new Date(d.start_date) < new Date(today)) {
                return 0.2;
            } else {
                return 1;
            }
        });
    dummyInstrumentBtn
        .select(".dummy-M")
        .append("text")
        .attr("x", 28)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2 + 10;
        })
        .attr("font-size", 10)
        .attr("text-anchor", "start")
        .attr("fill", "#fff")
        .text("M");
    // draw S button
    dummyInstrumentBtn
        .append("g")
        .attr("class", "dummy-S")
        .append("rect")
        .attr("x", 25 + eventHeight - 15)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2;
        })
        .attr("width", eventHeight - 15)
        .attr("height", eventHeight - 15)
        .attr("fill", "#fff")
        .attr("stroke", "#2d2a2d")
        .attr("opacity", 0.2);
    dummyInstrumentBtn
        .select(".dummy-S")
        .append("text")
        .attr("x", 25 + eventHeight - 15 + 5)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2 + 10;
        })
        .attr("font-size", 10)
        .attr("text-anchor", "start")
        .attr("fill", "#fff")
        .text("S");
    // dummy ○ button
    dummyInstrumentBtn
        .append("g")
        .attr("class", "dummy-circle")
        .append("rect")
        .attr("x", 25 + (eventHeight - 15) * 2)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2;
        })
        .attr("width", eventHeight - 15)
        .attr("height", eventHeight - 15)
        .attr("fill", (d, i) => {
            if (i == 0) {
                return "#D8346E";
            } else {
                return "#fff";
            }
        })
        .attr("stroke", "#2d2a2d")
        .attr("opacity", (d, i) => {
            if (i == 0) {
                return 1;
            } else {
                return 0.2;
            }
        });
    dummyInstrumentBtn
        .select(".dummy-circle")
        .append("circle")
        .attr("cx", 25 + (eventHeight - 15) * 2 + 7.5)
        .attr("cy", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2 + 7;
        })
        .attr("r", 3)
        .attr("fill", "#fff");
    // dummy round button 2
    dummyInstrumentBtn
        .append("g")
        .attr("class", "dummy-circle2")
        .append("rect")
        .attr("x", 25 + (eventHeight - 15) * 3)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2;
        })
        .attr("width", eventHeight - 15)
        .attr("height", eventHeight - 15)
        .attr("fill", (d, i) => {
            if (i == 0) {
                return "#62bbff";
            } else {
                return "#fff";
            }
        })
        .attr("stroke", "#2d2a2d")
        .attr("opacity", (d, i) => {
            if (i == 0) {
                return 1;
            } else {
                return 0.2;
            }
        });
    dummyInstrumentBtn
        .select(".dummy-circle2")
        .append("circle")
        .attr("cx", 25 + (eventHeight - 15) * 3 + 8)
        .attr("cy", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 2 + 7;
        })
        .attr("r", 3)
        .attr("fill", "#fff");
    if (width < 500) {
        dummyInstrumentBtn.attr("opacity", 0);
    }
};
const updateDrawInstrumentLabel = () => {
    instrumentLabelBg
        .attr("width", margin.left - 2)
        .attr("height", eventHeight);
    instrumentLabelRight.attr("x", margin.left - 6).attr("y", (d, i) => {
        return (eventHeight + eventPadding) * i + eventPadding;
    });
    if (width < 500) {
        dummyInstrumentBtn.attr("opacity", 0);
    } else {
        dummyInstrumentBtn.attr("opacity", 1);
    }
};
const updateInstrumentButton = () => {
    dummyInstrumentBtn.selectAll("rect").attr("fill", (d, i) => {
        if (new Date(d.start_date) < new Date(today)) {
            return "#fff";
        } else {
            return "#D8346E";
        }
    });
};

const drawEvent = () => {
    eventArea = mainArea
        .append("g")
        .attr("class", "event-area")
        .attr("transform", "translate(0," + 0 + ")");
    event = eventArea
        .selectAll("g")
        .data(events)
        .enter()
        .append("g")
        .attr("class", "event");
    eventBar = event
        .append("rect")
        .attr("class", "event-bar")
        .attr("x", (d) => {
            if (new Date(d.start_date) < new Date(today)) {
                return xScale(new Date(today));
            } else {
                return xScale(new Date(d.start_date));
            }
        })
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", (d) => {
            if (new Date(d.start_date) < new Date(today)) {
                return xScale(new Date(d.end_date)) - xScale(new Date(today));
            } else {
                return (
                    xScale(new Date(d.end_date)) -
                    xScale(new Date(d.start_date))
                );
            }
        })
        .attr("height", eventHeight)
        .attr("fill", (d, i) => {
            return cols(i / props.monthData.length);
        })
        .attr("stroke", "#fff")
        .attr("stroke-width", 0.3);
    eventLabel = event
        .append("g")
        .attr("class", "event-label")
        .attr("transform", (d, i) => {
            if (new Date(d.start_date) < new Date(today)) {
                return "translate(" + xScale(new Date(today)) + ",0)";
            } else {
                return "translate(" + xScale(new Date(d.start_date)) + ",0)";
            }
        });
    eventLabelText = eventLabel
        .append("text")
        .attr("class", "event-label-text")
        .attr("x", 5)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding + 15;
        })
        .attr("fill", "#fff")
        .attr("font-size", 15)
        .attr("text-anchor", "start")
        .attr("stroke-width", 0.1)
        .text((d) => {
            return d.event_title;
        });
    eventLabelBg = eventLabel
        .insert("rect", "text")
        .attr("x", 0)
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", function (d, i) {
            return eventLabelText.nodes()[i].getBBox().width + 10;
        })
        .attr("height", 20)
        .attr("fill", "#000")
        .attr("opacity", 0.5)
        .attr("class", "event-label");
};
const updateDrawEvent = () => {
    eventBar
        .attr("x", (d) => {
            if (new Date(d.start_date) < new Date(today)) {
                return xScale(new Date(today));
            } else {
                return xScale(new Date(d.start_date));
            }
        })
        .attr("y", (d, i) => {
            return (eventHeight + eventPadding) * i + eventPadding;
        })
        .attr("width", (d) => {
            if (new Date(d.start_date) < new Date(today)) {
                return xScale(new Date(d.end_date)) - xScale(new Date(today));
            } else {
                return (
                    xScale(new Date(d.end_date)) -
                    xScale(new Date(d.start_date))
                );
            }
        })
        .attr("height", eventHeight)
        .attr("fill", (d, i) => {
            return cols(i / props.monthData.length);
        })
        .attr("stroke", "#fff")
        .attr("stroke-width", 0.3);
    eventLabel.attr("transform", (d, i) => {
        if (new Date(d.start_date) < new Date(today)) {
            return "translate(" + xScale(new Date(today)) + ",0)";
        } else {
            return "translate(" + xScale(new Date(d.start_date)) + ",0)";
        }
    });
};
const updateEventLabelPosition = () => {
    eventLabel.attr("transform", (d) => {
        if (
            xScale(new Date(d.start_date)) <= -currentPosition &&
            xScale(new Date(d.end_date)) >= -currentPosition
        ) {
            return "translate(" + -currentPosition + ",0)";
        } else {
            return "translate(" + xScale(new Date(d.start_date)) + ",0)";
        }
    });
};

onMounted(() => {
    field = d3.select("#timeline-wrapper");
    if (window === "undefined") {
        return;
    }
    initial();
    window.addEventListener("load", function () {
        changeWidth();
    });
    window.addEventListener("resize", debounce(changeWidth, 500));
});
const initial = () => {
    width = timelineWrapper.value.clientWidth;
    if (width < 500) {
        margin.left = marginLeftSm;
    } else {
        margin.left = marginLeftLg;
    }
    graphArea = field.append("svg").attr("width", width).attr("height", height);
    graphArea.append("clipPath").attr("id", "clip-path");
    clipPath = graphArea
        .select("#clip-path")
        .append("rect")
        .attr("width", width)
        .attr("height", height)
        .attr("x", margin.left)
        .attr("y", 0);
    clipArea = graphArea.append("g").attr("clip-path", "url(#clip-path)");
    mainArea = clipArea
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
        .attr("class", "main-area");
    // init axis
    setAxis();
    drawAxis();
    drawEvent();
    drawInstrumentLabel();
    // zoom
    zoom = d3
        .zoom()
        .scaleExtent([1, 1])
        .translateExtent([
            [-margin.left, 0],
            [defScaleWidth + margin.right, 0],
        ])
        .on("zoom", zoomed);
    graphArea.call(zoom);
};
const zoomed = (e) => {
    const transform = e.transform;
    // limit left and right
    let translateX = transform.x;
    currentPosition = translateX - margin.left;
    // console.log(currentPosition);
    mainArea.attr(
        "transform",
        "translate(" + translateX + ", " + margin.top + ")"
    );
    // updateDrawEvent();
    updateEventLabelPosition();
};
</script>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
<template>
    <div
        class="relative rounded-t-lg bg-ptr-white px-4 pb-4 pt-8 before:absolute before:top-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:bg-ptr-pink"
    >
        <div
            class="relative max-h-96 w-full rounded-md bg-ptr-dark-brown"
            :style="`height: ${height}px`"
        >
            <div
                class="absolute top-0 left-0 mx-0 h-fit w-full min-w-full max-w-full overflow-hidden px-0"
                id="timeline-wrapper"
                ref="timelineWrapper"
            ></div>
        </div>
        <div class="ml-auto mt-4 mr-2 w-fit">
            <Link
                v-if="$page.props.auth.user"
                as="button"
                :href="route('create.schedule')"
                class="btn-secondary btn-sm btn text-white"
            >
                スケジュール登録
            </Link>
            <button
                v-else
                class="btn-secondary btn-sm btn text-white"
                @click="loginModal = true"
            >
                スケジュール登録
            </button>
            <Modal :show="loginModal" @close="loginModal = false">
                <div>
                    <h3 class="my-2 text-center text-lg">
                        ログインユーザーのみの機能です。
                    </h3>
                    <p class="my-3 text-center text-sm">
                        だまして悪いが、仕様なんでな　<Link
                            class="px-1n link-primary link"
                            :href="route('transition.login')"
                            >ログイン</Link
                        >してもらおう
                    </p>
                </div>
            </Modal>
        </div>
    </div>
</template>
