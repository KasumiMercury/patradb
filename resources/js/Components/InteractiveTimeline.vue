<script setup>
import TimeInput from "@/Components/TimeInput.vue";
import chroma from "chroma-js";
import * as d3 from "d3";
import pkg from "lodash";
import { onMounted, onUnmounted, ref, watch, inject } from "vue";
import FamiController from "./FamiController.vue";
import GameBoyController from "./GameBoyController.vue";
const { debounce, chunk, sortedIndexBy } = pkg;
const $cookies = inject("$cookies");

const isServer = typeof window === "undefined";
const props = defineProps({
    videoLength: Number,
    currentTime: Number,
    isTransfer: Boolean,
});
const emits = defineEmits(["playAt"]);
const timelineWrapper = ref();
const controllerWrapper = ref();

// setting
let width = 1000;
const ControllerWidth = ref(1000);
const height = { zoom: 60, zoomSub: 60, select: 180, player: 60 };
const margin = {
    top: 40,
    bottom: 40,
    left: 20,
    right: 50,
    space: 5,
    space2: 30,
};
const timeSection = [
    { range: 10, tick: 2, snap: 1 },
    { range: 30, tick: 5, snap: 1 },
    { range: 60, tick: 10, snap: 2 },
    { range: 120, tick: 30, snap: 5 },
    { range: 300, tick: 60, snap: 5 },
    { range: 600, tick: 120, snap: 30 },
    { range: 1800, tick: 300, snap: 30 },
    { range: 3600, tick: 600, snap: 180 },
    { range: 7200, tick: 600, snap: 180 },
];

const command = [
    "ArrowUp",
    "ArrowUp",
    "ArrowDown",
    "ArrowDown",
    "ArrowLeft",
    "ArrowRight",
    "ArrowLeft",
    "ArrowRight",
    "KeyB",
    "KeyA",
];

// global
let keyInputArray = [];
const timeSet = ref([]);
const menuSet = ref([]);
const timeSetArray = ref([]);
let selectData = null;
const snap = ref(true);
const focus = ref(false);
const errorExist = ref(false);
//
let brushRange = [0, 3600];
let brushRangeLength = 3600;
let brushSubRange = [];
let brushSubRangeLength = 0;
let zoomSubRnage = [];
let zoomSubRnageLength = 3600;
let brushRangePrev = [0, 3600];
let brushSubRangePrev = [];
let seelected = null;
let snapValue = 1;
let showSub = false;
let showedSub = false;
let timeVal5 = [];
let isPlayer = false;
let histry = [];
let histryIndex = -1;

// axis
let xScaleZoom = d3.scaleLinear();
let xAxisZoom = d3.axisTop();
let xScaleZoomSub = d3.scaleLinear();
let xAxisZoomSub = d3.axisBottom();
let xScaleMain = d3.scaleLinear();
let xScaleMain2 = null;
let xAxisMain = d3.axisBottom();

// elements
let field = null;
let graphArea = null;
let progressBar = null;
let progressAxis = null;
let mouseAxis = null;

// brush
let brush = null;
let brushSub = null;
let brushG = null;
let brushSubG = null;

// group
let zoomGroup = null;
let zoomSubGroup = null;
let mainGroup = null;
let selectGroup = null;
let playerGroup = null;
let timeZoomGroup = null;
let timeMainGroup = null;
let selectTimeGroup = null;

// color scale
const colorRange = ["#d93a4b", "#ffb3c2", "#3e3a8f"];
let cols = null;

onMounted(() => {
    field = d3.select("#timeline-wrapper");
    if (!isServer) {
        window.addEventListener("resize", debounce(changeWidth, 200));
    }
    // width setting
    width = timelineWrapper.value.clientWidth;
    ControllerWidth.value = controllerWrapper.value.clientWidth;
    // init group
    graphArea = field
        .append("svg")
        .attr("width", width)
        .attr(
            "height",
            height.zoom +
                height.zoomSub +
                height.select +
                height.player +
                margin.top +
                margin.bottom +
                margin.space +
                margin.space2
        );
    zoomGroup = graphArea
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
        .attr("class", "group zoom");
    zoomSubGroup = graphArea
        .append("g")
        .attr(
            "transform",
            "translate(" +
                margin.left +
                "," +
                Number(margin.top + height.zoom + margin.space) +
                ")"
        )
        .attr("class", "group zoom sub");
    mainGroup = graphArea
        .append("g")
        .attr(
            "transform",
            "translate(" +
                margin.left +
                "," +
                Number(margin.top + height.zoom) +
                ")"
        )
        .attr("class", "group main")
        .attr("fill", "#ffedf3")
        .on("mousemove click", mouseEvent);
    selectTimeGroup = mainGroup.append("g").attr("class", "time select group");
    selectGroup = mainGroup
        .append("rect")
        .attr("class", "select region")
        .attr("width", width - margin.left - margin.right)
        .attr("height", height.select)
        .attr("fill", "#c20063")
        .attr("fill-opacity", "0.1")
        .on("mouseover", function () {
            isPlayer = false;
        });
    playerGroup = mainGroup
        .append("rect")
        .attr("class", "player region")
        .attr("width", width - margin.left - margin.right)
        .attr("height", height.player)
        .attr("transform", "translate(0," + height.select + ")")
        .attr("fill", "#c5bfc2")
        .attr("fill-opacity", "0.3")
        .on("mouseover", function () {
            isPlayer = true;
        });
    // init axis
    setAxis();
    drawAxis();
    // timeGroup Zoom
    timeZoomGroup = zoomGroup.append("g").attr("class", "time zoom group");
    // timeBar Axis
    progressBar = zoomGroup
        .append("rect")
        .attr("class", "progress")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", xScaleZoom(props.currentTime))
        .attr("height", height.zoom)
        .attr("fill", "steelblue")
        .attr("fill-opacity", "0.5");
    progressAxis = mainGroup
        .append("rect")
        .attr("class", "current")
        .attr("x", xScaleMain2(props.currentTime) - 1)
        .attr("y", 0)
        .attr("width", 2)
        .attr("height", Number(height.player + height.select))
        .attr("fill", "#ff0000");
    // set brush
    brush = d3
        .brushX()
        .extent([
            [0, 0],
            [Number(width - margin.left - margin.right), height.zoom],
        ])
        .on("brush", brushed)
        .on("end", brushend);
    brushG = zoomGroup.append("g").attr("class", "x brush");
    brushG
        .call(brush)
        .selectAll(".overlay")
        .each(function (d) {
            d.type = "selection";
        })
        .on("mousedown ", brushSelection);
    brushG
        .select(".selection")
        .attr("fill", "#2d2a2d")
        .attr("fill-opacity", "0.2");
    brushSub = d3
        .brushX()
        .extent([
            [0, 0],
            [Number(width - margin.left - margin.right), height.zoomSub],
        ])
        .on("brush", brushedSub);
    brushSubG = zoomSubGroup.append("g").attr("class", "x brush sub");
    brushSubG
        .call(brushSub)
        .selectAll(".overlay")
        .each(function (d) {
            d.type = "selection";
        })
        .on("mousedown ", burshSubSelection);
    // mouse Axis
    mouseAxis = mainGroup.append("g").attr("class", "mouse group");
    mouseAxis
        .append("rect")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", 1)
        .attr("height", height.select + height.player)
        .attr("class", "axis mouse")
        .attr("fill", "#2d2a3d");
    mouseAxis
        .append("text")
        .attr("id", "tip")
        .attr("x", 10)
        .attr("y", 60)
        .attr("fill", "#2d2a3d")
        .style("user-select", "none");
    // timeGroup Select
    timeMainGroup = mainGroup.append("g").attr("class", "time main group");
    histry[0] = [];
});
// konami command
const onKeyDown = (e) => {
    keyInputArray.push(e);
    if (keyInputArray.length >= 10) {
        keyInputArray = keyInputArray.slice(-10);
        if (String(keyInputArray) === String(command)) {
            console.log("command fire");
        }
    }
};
// axis display
const setAxis = () => {
    xScaleZoom
        .domain([0, props.videoLength])
        .range([0, width - margin.left - margin.right]);
    xAxisZoom.scale(xScaleZoom);
    xScaleZoomSub = xScaleZoom.copy();
    xAxisZoomSub.scale(xScaleZoomSub);
    xScaleMain
        .domain([0, props.videoLength])
        .range([0, width - margin.left - margin.right]);
    xScaleMain2 = xScaleMain.copy();
    xAxisMain.scale(xScaleMain2);
};
const drawAxis = () => {
    zoomGroup
        .append("g")
        .call(
            xAxisZoom.tickValues(getDefaultTick()).tickFormat(function (input) {
                return getTickFormat(input);
            })
        )
        .attr("class", "x axis zoom")
        .style("user-select", "none")
        .selectAll("text")
        .attr("transform", "rotate(30)translate(0,-5)")
        .style("text-anchor", "end");
    mainGroup
        .append("g")
        .call(
            xAxisMain.tickValues(getDefaultTick()).tickFormat(function (input) {
                return getTickFormat(input);
            })
        )
        .attr(
            "transform",
            "translate(0," + Number(height.select + height.player) + ")"
        )
        .attr("class", "x axis main")
        .style("user-select", "none")
        .selectAll("text")
        .attr("transform", "rotate(30)")
        .style("text-anchor", "start");
    zoomSubGroup
        .append("g")
        .call(
            xAxisZoomSub.tickValues(timeVal5).tickFormat(function (input) {
                return getTickFormat(input);
            })
        )
        .attr("transform", "translate(0," + height.zoomSub + ")")
        .attr("class", "x axis zoom sub")
        .style("user-select", "none")
        .selectAll("text")
        .attr("transform", "rotate(30)")
        .style("text-anchor", "start");
    zoomSubGroup.classed("hidden", true);
};
const updateAxisMain = () => {
    let t = d3.transition().duration(200).ease(d3.easeLinear);
    mainGroup
        .select(".axis")
        .transition(t)
        .call(
            xAxisMain.tickValues(getTickValues()).tickFormat(function (input) {
                return getTickFormat(input);
            })
        )
        .selectAll("text")
        .attr("transform", "rotate(30)")
        .style("text-anchor", "start");
};
const updateAxisZoom = () => {
    let t = d3.transition().duration(200);
    zoomGroup
        .select(".axis")
        .transition(t)
        .call(
            xAxisZoom.tickValues(getDefaultTick()).tickFormat(function (input) {
                return getTickFormat(input);
            })
        )
        .selectAll("text")
        .attr("transform", "rotate(30)translate(0,-5)")
        .style("text-anchor", "end");
};
const updateAxisZoomSub = () => {
    updateZoomSub();
    brushSubG.call(brushSub.move, [
        xScaleZoomSub(brushRange[0]),
        xScaleZoomSub(brushRange[1]),
    ]);
};
const showZoomSub = () => {
    let t = d3.transition().duration(200);
    mainGroup
        .transition(t)
        .attr(
            "transform",
            "translate(" +
                margin.left +
                "," +
                Number(
                    margin.top +
                        height.zoom +
                        height.zoomSub +
                        margin.space +
                        margin.space2
                ) +
                ")"
        );
    zoomSubGroup.classed("hidden", false);
    zoomSubGroup.transition(t).attr("height", height.zoomSub);
    showSub = true;
    updateAxisMain();
    updateTimeMainDisplay();
};
const hideZoomSub = () => {
    let t = d3.transition().duration(200);
    zoomSubGroup.classed("hidden", true);
    zoomSubGroup.transition(t).attr("height", "0");
    mainGroup
        .transition(t)
        .attr(
            "transform",
            "translate(" +
                margin.left +
                "," +
                Number(margin.top + height.zoom) +
                ")"
        );
    showSub = false;
    updateAxisMain();
    updateTimeMainDisplay();
};
const updateZoomSub = () => {
    let center = (brushRange[0] + brushRange[1]) / 2;
    let start = center - brushRangeLength / 2 - 60;
    let end = center + brushRangeLength / 2 + 60;
    if (center > props.videoLength - brushRangeLength / 2 - 60) {
        end = props.videoLength;
        start = props.videoLength - brushRangeLength - 120;
    }
    if (center < brushRangeLength / 2 + 60) {
        start = 0;
        end = brushRangeLength + 120;
    }
    zoomSubRnage = [start, end];
    zoomSubRnageLength = end - start;
    xScaleZoomSub
        .domain([start, end])
        .range([0, width - margin.left - margin.right]);
    xAxisZoomSub.scale(xScaleZoomSub);
    let t = d3.transition().duration(200);
    zoomSubGroup
        .select(".axis")
        .transition(t)
        .call(
            xAxisZoomSub
                .tickValues(getZoomSubTick())
                .tickFormat(function (input) {
                    return getTickFormat(input);
                })
        )
        .attr("transform", "translate(0," + height.zoomSub + ")")
        .attr("class", "x axis zoom sub")
        .style("user-select", "none")
        .selectAll("text")
        .attr("transform", "rotate(30)")
        .style("text-anchor", "start");
};
// generate tick_info
const getDefaultTick = () => {
    let tickArray = [];
    if (Math.floor(props.videoLength / 1800) < 6) {
        let length = Math.floor(props.videoLength / 1800);
        for (let i = 0; i < length; i++) {
            tickArray.push(1800 * i);
        }
        if (props.videoLength % 1800 > 300 || props.videoLength % 1800 == 0) {
            tickArray.push(1800 * length);
        }
    } else {
        let length = Math.floor(props.videoLength / 3600);
        for (let i = 0; i < length; i++) {
            tickArray.push(3600 * i);
        }
        if (props.videoLength % 1800 > 300 || props.videoLength % 1800 == 0) {
            tickArray.push(3600 * length);
        }
    }
    tickArray.push(props.videoLength);
    return tickArray;
};
const getTickValues = () => {
    let length = brushRangeLength;
    let range = brushRange;
    if (showSub) {
        length = brushSubRangeLength;
        range = brushSubRange;
    }
    if (length >= 10800) {
        snapValue = 300;
        return getDefaultTick();
    } else {
        let nearIndex = sortedIndexBy(timeSection, { range: length }, "range");
        if (nearIndex != 0) {
            snapValue = timeSection[nearIndex - 1].snap;
            return generateTickArray(timeSection[nearIndex - 1].tick, range);
        }
    }
};
const getZoomSubTick = () => {
    if (zoomSubRnageLength >= 10800) {
        snapValue = 300;
        return getDefaultTick();
    } else {
        let nearIndex = sortedIndexBy(
            timeSection,
            { range: zoomSubRnageLength },
            "range"
        );
        if (nearIndex != 0) {
            snapValue = timeSection[nearIndex - 1].snap;
            return generateTickArray(
                timeSection[nearIndex - 1].tick,
                zoomSubRnage
            );
        }
    }
};
const generateTickArray = (tick, range) => {
    let tickArray = [];
    for (
        let i = Math.floor(range[0] / tick) + 1;
        i <= Math.floor(range[1] / tick);
        i++
    ) {
        tickArray.push(tick * i);
    }
    return tickArray;
};
const getTickFormat = (input) => {
    if (input == props.videoLength) {
        return "end";
    } else if (input % 1800 == 0) {
        return input / 3600 + "h";
    } else {
        let hour = Math.floor(input / 3600);
        let min = Math.floor((input % 3600) / 60);
        let sec = input % 60;
        if (sec != 0) {
            return hour + "h" + min + "m" + sec + "s";
        } else {
            return hour + "h" + min + "m";
        }
    }
};
// brush
const brushed = ({ selection }) => {
    let [x0, x1] = selection.map(xScaleMain.invert);
    brushRange = [x0, x1];
    brushRangeLength = brushRange[1] - brushRange[0];
    xScaleMain2.domain([x0, x1]).range([0, width - margin.left - margin.right]);
    xAxisMain.scale(xScaleMain2);
    updateAxisMain();
    updateTimeMainDisplay();
    if (brushRangeLength < 3600) {
        if (showSub === false) {
            showZoomSub();
        }
    } else {
        if (showSub == true) {
            hideZoomSub();
        }
    }
};
const brushend = () => {
    if (showSub) {
        updateZoomSub();
        brushSubG.call(brushSub.move, [
            xScaleZoomSub(brushRange[0]),
            xScaleZoomSub(brushRange[1]),
        ]);
    }
};
const brushSelection = (event) => {
    let mouse = d3.pointer(event);
    let dx = xScaleZoom(brushRange[1] - brushRange[0]);
    let x0 = mouse[0] - dx / 2;
    let x1 = mouse[0] + dx / 2;
    let xMax = xScaleZoom.range()[1];
    let x = x1 > xMax ? [xMax - dx, xMax] : x0 < 0 ? [0, dx] : [x0, x1];
    brushG.call(brush.move, [x[0], x[1]]);
};
const brushedSub = ({ selection }) => {
    let [x0, x1] = selection.map(xScaleZoomSub.invert);
    brushSubRange = [x0, x1];
    brushSubRangeLength = brushSubRange[1] - brushSubRange[0];
    xScaleMain2.domain([x0, x1]).range([0, width - margin.left - margin.right]);
    xAxisMain.scale(xScaleMain2);
    updateAxisMain();
    updateTimeMainDisplay();
};
const burshSubSelection = (event) => {
    let mouse = d3.pointer(event);
    let dx = xScaleZoomSub(brushSubRange[1]) - xScaleZoomSub(brushSubRange[0]);
    let x0 = mouse[0] - dx / 2;
    let x1 = mouse[0] + dx / 2;
    let xMax = xScaleZoomSub.range()[1];
    let x = x1 > xMax ? [xMax - dx, xMax] : x0 < 0 ? [0, dx] : [x0, x1];
    brushSubG.call(brushSub.move, [x[0], x[1]]);
};
// progress display
const updateProgress = () => {
    let t = d3.transition().duration(100);
    progressBar.transition(t).attr("width", xScaleZoom(props.currentTime));
    updateCurrentAxis();
};
const updateCurrentAxis = () => {
    let t = d3.transition().duration(100);
    progressAxis.transition(t).attr("x", xScaleMain2(props.currentTime) - 1);
};
// mouse behavier
const mouseEvent = (event) => {
    let mouse = d3.pointer(event);
    let time = Math.floor(xScaleMain2.invert(mouse[0]));
    if (event.type == "mousemove") {
        mouseAxis.attr("transform", "translate(" + xScaleMain2(time) + ",0)");
        mouseAxis.select("#tip").text(convertTime(time));
    } else {
        if (isPlayer) {
            clickPlay(time);
        } else {
            addTime(time);
        }
    }
};
const clickPlay = (time) => {
    emits("playAt", time);
};
const addTime = (time) => {
    if (!timeSet.value.includes(Math.floor(time))) {
        timeSet.value.push(Math.floor(time));
        updateTimeArray();
        histry.push([...timeSet.value]);
        histryIndex++;
        generateColScale();
        updateTimeZoomDisplay();
        updateTimeMainDisplay();
        menuSet.value.push(false);
    }
};
const updateTimeZoomDisplay = () => {
    let tempZoom = timeZoomGroup.selectAll("rect").data(timeSetArray.value);
    tempZoom.exit().remove();
    tempZoom
        .enter()
        .append("rect")
        .merge(tempZoom)
        .attr("x", function (d) {
            return xScaleZoom(d[0]);
        })
        .attr("y", 0)
        .attr("width", function (d) {
            if (d.length == 1) {
                return 1;
            } else {
                let timeWidth = xScaleZoom(d[1]) - xScaleZoom(d[0]);
                if (timeWidth < 1) {
                    return 1;
                } else {
                    return timeWidth;
                }
            }
        })
        .attr("height", height.zoom)
        .attr("fill", function (d, i) {
            return cols(i / d.length);
        })
        .attr("fill-opacity", "0.5")
        .style("user-select", "none");
};
const updateTimeMainDisplay = () => {
    // main Area selectHandle
    let bar = timeMainGroup.selectAll("rect").data(timeSet.value);
    bar.exit().remove();
    bar.enter()
        .append("rect")
        .attr("y", 0)
        .attr("width", 4)
        .attr("height", height.select)
        .merge(bar)
        .attr("x", function (d, i) {
            return xScaleMain2(d) - (i % 2) * 4;
        })
        .attr("fill", function (d, i) {
            return cols(Math.floor(i / 2) / timeSetArray.value.length);
        });
    let handle = timeMainGroup.selectAll("polygon").data(timeSet.value);
    handle.exit().remove();
    handle
        .enter()
        .append("polygon")
        .attr("points", "0,0 30,0 0,30")
        .merge(handle)
        .attr("transform", function (d, i) {
            return (
                "translate(" +
                Number(xScaleMain2(d) - (i % 2) * 4) +
                "," +
                Number(height.select * (i % 2)) +
                ")rotate(" +
                Number(180 * (i % 2)) +
                ")"
            );
        })
        .attr("fill", function (d, i) {
            return cols(Math.floor(i / 2) / timeSetArray.value.length);
        })
        .call(
            d3
                .drag()
                .on("start", function (event, d) {
                    seelected = d3.select(this);
                    timeSelect(d);
                })
                .on("drag", function (event, d) {
                    timeDrag(event, d);
                })
                .on("end", function (event) {
                    timeDragged(event);
                })
        );
    // main Area back
    let timeArea = selectTimeGroup.selectAll("rect").data(timeSetArray.value);
    timeArea.exit().remove();
    timeArea
        .enter()
        .append("rect")
        .attr("y", 0)
        .attr("height", height.select)
        .attr("fill-opacity", "0.5")
        .merge(timeArea)
        .attr("x", function (d) {
            return xScaleMain2(d[0]);
        })
        .attr("width", function (d) {
            if (d.length == 1) {
                return 0;
            } else {
                return xScaleMain2(d[1]) - xScaleMain2(d[0]);
            }
        })
        .attr("fill", function (d, i) {
            return cols(i / timeSetArray.value.length);
        });
};
const generateColScale = () => {
    let classNum = 4;
    if (classNum < timeSet.value.length / 2) {
        classNum = timeSet.value.length / 2;
    }
    cols = chroma.scale(colorRange).classes(classNum).mode("lch");
};
const updateTimeArray = () => {
    timeSet.value.sort(function (a, b) {
        return a - b;
    });
    timeSetArray.value = chunk(timeSet.value, 2);
    errorExist.value = false;
    for (let i = 0; i < timeSetArray.value.length; i++) {
        if (timeSetArray.value[i].length == 1) {
            errorExist.value = true;
        }
    }
    $cookies.set("playerTimeArray", [...timeSet.value]);
};

// time function
const timeSelect = (select) => {
    selectData = select;
    seelected.attr("stroke-width", 3).attr("stroke", "white");
};
const timeDrag = (event, select) => {
    let time = Math.floor(xScaleMain2.invert(event.x));
    mouseAxis.attr("transform", "translate(" + xScaleMain2(time) + ",0)");
    mouseAxis.select("#tip").text(convertTime(time));
    timeMainGroup
        .selectAll("rect")
        .filter(function (d) {
            return d === select;
        })
        .attr("x", function () {
            return xScaleMain2(time) - (timeSet.value.indexOf(select) % 2) * 4;
        });
    timeMainGroup
        .selectAll("polygon")
        .filter(function (d) {
            return d === select;
        })
        .attr("transform", function () {
            return (
                "translate(" +
                Number(
                    xScaleMain2(time) - (timeSet.value.indexOf(select) % 2) * 4
                ) +
                "," +
                Number(height.select * (timeSet.value.indexOf(select) % 2)) +
                ")rotate(" +
                Number(180 * (timeSet.value.indexOf(select) % 2)) +
                ")"
            );
        })
        .call(
            d3
                .drag()
                .on("start", function (event, d) {
                    timeSelect(d);
                })
                .on("drag", function (event, d) {
                    timeDrag(event, d);
                })
                .on("end", function (event) {
                    timeDragged(event);
                })
        );
};
const timeDragged = (event) => {
    timeSet.value[timeSet.value.indexOf(selectData)] = Math.floor(
        xScaleMain2.invert(event.x)
    );
    updateTimeArray();
    updateTimeZoomDisplay();
    updateTimeMainDisplay();
    seelected.attr("stroke", "none");
};

// fami controller methods
const brushNext = () => {
    onKeyDown("ArrowRight");
    if (showSub) {
        let center =
            (brushSubRange[0] + brushSubRange[1]) / 2 + brushSubRangeLength / 2;
        let start = center - brushSubRangeLength / 2;
        let end = center + brushSubRangeLength / 2;
        if (center > zoomSubRnage[1] - brushSubRangeLength / 2) {
            end = zoomSubRnage[1];
            start = zoomSubRnage[1] - brushSubRangeLength;
        }
        brushSubG.call(brushSub.move, [
            xScaleZoomSub(start),
            xScaleZoomSub(end),
        ]);
    } else {
        let center = (brushRange[0] + brushRange[1]) / 2 + brushRangeLength / 2;
        let start = center - brushRangeLength / 2;
        let end = center + brushRangeLength / 2;
        if (center > props.videoLength - brushRangeLength / 2) {
            end = props.videoLength;
            start = props.videoLength - brushRangeLength;
        }
        brushG.call(brush.move, [xScaleZoom(start), xScaleZoom(end)]);
    }
};
const brushPrev = () => {
    onKeyDown("ArrowLeft");
    if (showSub) {
        let center =
            (brushSubRange[0] + brushSubRange[1]) / 2 - brushSubRangeLength / 2;
        let start = center - brushSubRangeLength / 2;
        let end = center + brushSubRangeLength / 2;
        if (center < zoomSubRnage[0] + brushSubRangeLength / 2) {
            start = zoomSubRnage[0];
            end = zoomSubRnage[0] + brushSubRangeLength;
        }
        brushSubG.call(brushSub.move, [
            xScaleZoomSub(start),
            xScaleZoomSub(end),
        ]);
    } else {
        let center = (brushRange[0] + brushRange[1]) / 2 - brushRangeLength / 2;
        let start = center - brushRangeLength / 2;
        let end = center + brushRangeLength / 2;
        if (center < brushRangeLength / 2) {
            start = 0;
            end = brushRangeLength;
        }
        brushG.call(brush.move, [xScaleZoom(start), xScaleZoom(end)]);
    }
};
const controllAdd = () => {
    onKeyDown("KeyA");
    addTime(props.currentTime);
};
const undoOperate = () => {
    onKeyDown("KeyB");
    if (histryIndex > -1) {
        timeSet.value = histry[histryIndex];
        histryIndex--;
        histry.pop();
        if (histryIndex == -1) {
            histry[0] = [];
        }
        updateTimeArray();
        generateColScale();
        updateTimeZoomDisplay();
        updateTimeMainDisplay();
    }
};
const focusToggle = () => {
    let center = (brushRange[0] + brushRange[1]) / 2;
    focus.value = !focus.value;
    if (focus.value) {
        showedSub = showSub;
        if (showSub) {
            brushSubRangePrev = brushSubRange;
            brushSubG.call(brushSub.move, [
                xScaleZoomSub(center - 10),
                xScaleZoomSub(center + 10),
            ]);
        } else {
            brushRangePrev = brushRange;
            let dx = xScaleZoom(1200);
            let x0 = xScaleZoom(center) - dx / 2;
            let x1 = xScaleZoom(center) + dx / 2;
            let xMax = xScaleZoom.range()[1];
            let x = x1 > xMax ? [xMax - dx, xMax] : x0 < 0 ? [0, dx] : [x0, x1];
            brushG.call(brush.move, [x[0], x[1]]);
            brushSubG.call(brushSub.move, [
                xScaleZoomSub(center - 10),
                xScaleZoomSub(center + 10),
            ]);
        }
    } else {
        if (showedSub) {
            brushSubG.call(brushSub.move, [
                xScaleZoomSub(brushSubRangePrev[0]),
                xScaleZoomSub(brushSubRangePrev[1]),
            ]);
        } else {
            brushG.call(brush.move, [
                xScaleZoom(brushRangePrev[0]),
                xScaleZoom(brushRangePrev[1]),
            ]);
        }
    }
};
// find near range from timeSection array
const findNearLowRange = () => {
    onKeyDown("ArrowDown");
    let nearIndex = sortedIndexBy(
        timeSection,
        { range: brushRangeLength },
        "range"
    );
    if (nearIndex != 0) {
        if (timeSection[nearIndex - 1].range != Math.floor(brushRangeLength)) {
            brushRangeControll(timeSection[nearIndex - 1].range);
        } else {
            if (nearIndex - 1 != 0) {
                brushRangeControll(timeSection[nearIndex - 2].range);
            }
        }
    }
};
const findNearHighRange = () => {
    onKeyDown("ArrowUp");
    let nearIndex = sortedIndexBy(
        timeSection,
        { range: brushRangeLength },
        "range"
    );
    if (nearIndex != timeSection.length) {
        if (timeSection[nearIndex].range != Math.floor(brushRangeLength)) {
            brushRangeControll(timeSection[nearIndex].range);
        } else {
            if (nearIndex + 1 != timeSection.length) {
                brushRangeControll(timeSection[nearIndex + 1].range);
            }
        }
    }
};
const brushRangeControll = (range) => {
    brushRangeLength = range;
    let center = (brushRange[0] + brushRange[1]) / 2;
    let start = center - brushRangeLength / 2;
    let end = center + brushRangeLength / 2;
    if (center > props.videoLength - brushRangeLength / 2) {
        end = props.videoLength;
        start = props.videoLength - brushRangeLength;
    }
    if (center < brushRangeLength / 2) {
        start = 0;
        end = brushRangeLength;
    }
    brushG.call(brush.move, [xScaleZoom(start), xScaleZoom(end)]);
};

watch(
    () => props.videoLength,
    () => {
        setAxis();
        updateAxisZoom();
        updateAxisMain();

        let length = Math.floor(props.videoLength / 300);
        for (let i = 0; i < length; i++) {
            timeVal5.push(300 * i);
        }
        if (props.videoLength % 300 > 150 || props.videoLength % 300 == 0) {
            timeVal5.push(300 * length);
        }
        timeVal5.push(props.videoLength);

        brushG.call(brush.move, [0, xScaleZoom(3630)]);

        if (props.isTransfer && $cookies.isKey("playerTimeArray")) {
            let temp = $cookies.get("playerTimeArray");
            timeSet.value = temp;
            histry.push([...timeSet.value]);
            histryIndex++;
            updateTimeArray();
            generateColScale();
            updateTimeZoomDisplay();
            updateTimeMainDisplay();
        }
    }
);
watch(
    () => props.currentTime,
    () => {
        updateProgress();
    }
);

const changeTime = () => {
    updateTimeArray();
    histry.push([...timeSet.value]);
    histryIndex++;
    generateColScale();
    updateTimeZoomDisplay();
    updateTimeMainDisplay();
};
const changeWidth = () => {
    width = timelineWrapper.value.clientWidth;
    ControllerWidth.value = controllerWrapper.value.clientWidth;

    field.attr("width", width);
    graphArea.attr("width", width);
    selectGroup.attr("width", width - margin.left - margin.right);
    playerGroup.attr("width", width - margin.left - margin.right);
    zoomSubGroup.attr("width", width - margin.left - margin.right);
    brush.extent([
        [0, 0],
        [width - margin.left - margin.right, height.zoom],
    ]);
    brushG
        .call(brush)
        .selectAll(".overlay")
        .each(function (d) {
            d.type = "selection";
        })
        .on("mousedown touchstart", brushSelection);
    xScaleZoom
        .domain([0, props.videoLength])
        .range([0, width - margin.left - margin.right]);
    xAxisZoom.scale(xScaleZoom);
    xScaleZoomSub = xScaleZoom.copy();
    xAxisZoomSub.scale(xScaleZoomSub);
    xScaleMain
        .domain([0, props.videoLength])
        .range([0, width - margin.left - margin.right]);
    xScaleMain2
        .domain(brushRange)
        .range([0, width - margin.left - margin.right]);
    xAxisMain.scale(xScaleMain2);
    updateAxisMain();
    updateAxisZoom();
    updateAxisZoomSub();
    updateTimeZoomDisplay();
    updateTimeMainDisplay();
    updateZoomSub();
    updateProgress();
};
const convertTime = (input) => {
    let hour = Math.floor(input / 3600);
    let min = ("0" + Math.floor((input % 3600) / 60)).slice(-2);
    let sec = ("0" + (input % 60)).slice(-2);
    return hour + ":" + min + ":" + sec;
};
const showMenu = (index) => {
    let newArray = menuSet.value.map((element, i) => {
        if (i == index) {
            return !element;
        } else {
            return false;
        }
    });
    menuSet.value = newArray;
};
const removeTime = (index) => {
    timeSet.value.splice(index, 1);
    menuSet.value.splice(index, 1);
    changeTime();
};
onUnmounted(() => {
    if (!isServer) {
        window.removeEventListener("resize", changeWidth);
    }
});
</script>
<style scoped>
svg {
    width: 100% !important;
}
.selected {
    stroke: #fff !important;
}
.hidden {
    display: none;
}

#timeline-wrapper {
    widows: 100% !important;
}
</style>
<template>
    <div class="w-full">
        <div class="relative mb-32 h-96 w-full">
            <div
                class="absolute top-0 left-0 h-fit w-full min-w-full max-w-full overflow-hidden"
                id="timeline-wrapper"
                ref="timelineWrapper"
            ></div>
        </div>
        <!-- controller -->
        <div class="w-full" ref="controllerWrapper">
            <FamiController
                v-if="ControllerWidth > 980"
                :focus="focus"
                :snap="snap"
                @brushNext="brushNext"
                @brushPrev="brushPrev"
                @focusToggle="focusToggle"
                @LowerRange="findNearLowRange"
                @HigherRange="findNearHighRange"
                @undoOperate="undoOperate"
                @addTime="controllAdd"
            ></FamiController>
            <GameBoyController
                v-else
                :focus="focus"
                :snap="snap"
                @brushNext="brushNext"
                @brushPrev="brushPrev"
                @focusToggle="focusToggle"
                @LowerRange="findNearLowRange"
                @HigherRange="findNearHighRange"
                @undoOperate="undoOperate"
                @addTime="controllAdd"
            ></GameBoyController>
        </div>
        <!-- time dsiplay components -->
        <div
            class="grid px-4"
            :class="
                timeSetArray.length === 1
                    ? 'mx-auto w-full md:w-1/2'
                    : 'w-full md:grid-cols-2 md:gap-4'
            "
        >
            <div
                v-for="(times, index) in timeSetArray"
                :key="index"
                class="mt-2 mb-auto h-fit w-full rounded-xl p-3"
                :style="
                    'background-color:' +
                    cols(index / timeSetArray.length) +
                    ';'
                "
            >
                <TimeInput
                    type="time"
                    step="1"
                    class="mt-2 mb-1 w-full"
                    v-model="timeSet[index * 2]"
                    :isOpenMenu="menuSet[index * 2]"
                    required
                    @playAt="clickPlay(timeSet[index * 2])"
                    @remove="removeTime(index * 2)"
                    @change="changeTime"
                    @adjust="changeTime"
                    @showMenu="showMenu(index * 2)"
                ></TimeInput>
                <TimeInput
                    v-if="times.length !== 1"
                    type="time"
                    step="1"
                    class="mt-1 mb-2 w-full"
                    v-model="timeSet[index * 2 + 1]"
                    :isOpenMenu="menuSet[index * 2 + 1]"
                    required
                    @playAt="clickPlay(timeSet[index * 2 + 1])"
                    @remove="removeTime(index * 2 + 1)"
                    @change="changeTime"
                    @adjust="changeTime"
                    @showMenu="showMenu(index * 2 + 1)"
                ></TimeInput>
            </div>
        </div>
        <div
            class="mx-auto mt-16 mb-96 flex w-fit max-w-7xl flex-col px-8 pb-24 text-center lg:mt-24"
        >
            <p
                v-if="timeSet.length > 1 && errorExist"
                class="mb-3 mt-6 text-right text-red-600"
            >
                登録タイムがひとつのみのブロックがあります。当該ブロックはデータ登録時無視されます。
            </p>
            <button
                v-if="timeSet.length > 1"
                class="mx-auto flex w-fit items-center whitespace-nowrap rounded-lg bg-ptr-dark-pink py-1 px-3 text-xl text-white shadow-sm shadow-[#550341] lg:py-2 lg:px-6 lg:text-3xl"
            >
                Data Launch
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 512"
                    class="h-4 w-4 animate-side-bounce fill-white pl-3 lg:h-6 lg:w-6"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
