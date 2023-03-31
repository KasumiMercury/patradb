<script setup>
import TimeInput from "@/Components/TimeInput.vue";
import chroma from "chroma-js";
import * as d3 from "d3";
import pkg from "lodash";
import { onMounted, onUnmounted, ref, watch } from "vue";
const { debounce, chunk, sortedIndexBy } = pkg;

const isServer = typeof window === "undefined";
const props = defineProps({
    videoLength: Number,
    currentTime: Number,
});
const emits = defineEmits(["playAt"]);
const timelineWrapper = ref();

// setting
let width = 1000;
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

// global
const timeSet = ref([]);
let timeSetArray = [];
let selectData = null;
//
let brushRange = [0, 3600];
let brushRangeLength = 3600;
let brushRangePrev = [0, 3600];
let seelected = null;
let snapValue = 1;
let showSub = false;
let timeVal5 = [];
let isPlayer = false;
let zoomSubRnage = [];
let histry = [];
let histryIndex = -1;
let snap = true;
let focus = false;

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
        window.addEventListener("resize", changeWidth);
    }
    // width setting
    width = timelineWrapper.value.clientWidth;
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
        .on("mousedown touchstart", brushSelection);
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
        .on("mousedown touchstart", burshSubSelection);
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
    xScaleZoomSub
        .domain([start, end])
        .range([0, width - margin.left - margin.right]);
    xAxisZoomSub.scale(xScaleZoomSub);
    let t = d3.transition().duration(200);
    zoomSubGroup
        .select(".axis")
        .transition(t)
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
    if (brushRangeLength >= 10800) {
        snapValue = 300;
        return getDefaultTick();
    } else if (brushRangeLength <= 10) {
        snapValue = 1;
        return generateTickArray(1);
    } else {
        let underSectionArray = timeSection.filter(
            (section) => section.range <= brushRangeLength
        );
        let nearSection = underSectionArray[underSectionArray.length - 1];
        snapValue = nearSection["snap"];
        return generateTickArray(nearSection["tick"]);
    }
};
const generateTickArray = (tick) => {
    let tickArray = [];
    for (
        let i = Math.floor(brushRange[0] / tick) + 1;
        i <= Math.floor(brushRange[1] / tick);
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
    brushRange = [x0, x1];
    brushRangeLength = brushRange[1] - brushRange[0];
    xScaleMain2.domain([x0, x1]).range([0, width - margin.left - margin.right]);
    xAxisMain.scale(xScaleMain2);
    updateAxisMain();
    updateTimeMainDisplay();
};
const burshSubSelection = (event) => {
    let mouse = d3.pointer(event);
    let dx = xScaleZoomSub(brushRange[1]) - xScaleZoomSub(brushRange[0]);
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
    }
};
const updateTimeZoomDisplay = () => {
    let tempZoom = timeZoomGroup.selectAll("rect").data(timeSetArray);
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
            return cols(i / timeSetArray.length);
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
            return cols(Math.floor(i / 2) / timeSetArray.length);
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
            return cols(Math.floor(i / 2) / timeSetArray.length);
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
    let timeArea = selectTimeGroup.selectAll("rect").data(timeSetArray);
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
            return cols(i / timeSetArray.length);
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
    timeSetArray = chunk(timeSet.value, 2);
};

// find near range from timeSection array
const findNearLowRange = () => {
    let nearIndex = sortedIndexBy(
        timeSection,
        { range: brushRangeLength },
        "range"
    );
    if (nearIndex != 0) {
        if (timeSection[nearIndex - 1].range != Math.floor(brushRangeLength)) {
            snapBrushRange(timeSection[nearIndex - 1].range);
        } else {
            if (nearIndex - 1 != 0) {
                snapBrushRange(timeSection[nearIndex - 2].range);
            }
        }
    }
};
const findNearHighRange = () => {
    let nearIndex = sortedIndexBy(
        timeSection,
        { range: brushRangeLength },
        "range"
    );
    if (nearIndex != timeSection.length) {
        if (timeSection[nearIndex].range != Math.floor(brushRangeLength)) {
            snapBrushRange(timeSection[nearIndex].range);
        } else {
            if (nearIndex + 1 != timeSection.length) {
                snapBrushRange(timeSection[nearIndex + 1].range);
            }
        }
    }
};
const snapBrushRange = (range) => {
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

// brush function
const brushNext = () => {
    let center = (brushRange[0] + brushRange[1]) / 2 + brushRangeLength / 2;
    let start = center - brushRangeLength / 2;
    let end = center + brushRangeLength / 2;
    if (center > props.videoLength - brushRangeLength / 2) {
        end = props.videoLength;
        start = props.videoLength - brushRangeLength;
    }
    brushG.call(brush.move, [xScaleZoom(start), xScaleZoom(end)]);
};
const brushPrev = () => {
    let center = (brushRange[0] + brushRange[1]) / 2 - brushRangeLength / 2;
    let start = center - brushRangeLength / 2;
    let end = center + brushRangeLength / 2;
    if (center < brushRangeLength / 2) {
        start = 0;
        end = brushRangeLength;
    }
    brushG.call(brush.move, [xScaleZoom(start), xScaleZoom(end)]);
};

// time function
const timeSelect = (select) => {
    selectData = select;
    dragDiff = 0;
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

const undoOperate = () => {
    if (histryIndex > -1) {
        timeSet.value = histry[histryIndex];
        histryIndex--;
        histry.pop();
        console.log(histry);
        if (histryIndex == -1) {
            histry[0] = [];
        }
        console.log(histry);
        updateTimeArray();
        generateColScale();
        updateTimeZoomDisplay();
        updateTimeMainDisplay();
    }
};

const focusToggle = () => {
    let center = (brushRange[0] + brushRange[1]) / 2;
    focus = !focus;
    if (focus) {
        brushRangePrev = brushRange;
        if (showSub) {
            brushSubG.call(brushSub.move, [
                xScaleZoomSub(center - 10),
                xScaleZoomSub(center + 10),
            ]);
        } else {
            brushG.call(brush.move, [
                xScaleZoom(center - 10),
                xScaleZoom(center + 10),
            ]);
        }
    } else {
        brushG.call(brush.move, [
            xScaleZoom(brushRangePrev[0]),
            xScaleZoom(brushRangePrev[1]),
        ]);
    }
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
.controller_body {
    position: relative;
}
.controller_body::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    background-color: #c20063;
    width: 60%;
    height: 40%;
    z-index: 40;
    border-radius: 1rem;
}
.controller_gold {
    position: relative;
}
.controller_gold::after {
    content: "";
    position: absolute;
    bottom: 20px;
    left: 0;
    background-color: #f6e0af;
    width: 70%;
    height: 100%;
    z-index: 5;
    border-radius: 1rem;
}
.round_button {
    background: radial-gradient(circle at 30% 10%, #faf4fa 2%, #2d2a2d 20%);
}
.round_button::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 80%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: radial-gradient(
        circle at 30% 10%,
        rgba(225, 225, 225, 0.3),
        rgba(225, 225, 225, 0.2) 50%,
        transparent 60%
    );
}
.round_ellipse_button {
    /* background: linear-gradient(to bottom, #faf4fa 2%, #2d2a2d 20%); */
    background-color: #2d2a2d;
    border-width: 0.21rem;
    border-color: rgba(194, 183, 194, 0.1);
}
.rounded_ellipse_wrap {
    width: 35%;
    height: 60%;
}
.rounded_ellipse_wrap::after {
    width: 100%;
    height: 100%;
    box-sizing: content-box;
    border-width: 4px;
}
.ellipse_buttons_wrap {
    background: linear-gradient(to bottom, #75003b 40%, rgb(194, 0, 99) 60%);
}
.ellipse_buttons_wrap::before {
    content: "";
    position: absolute;
    width: calc(100% / 3);
    height: 100%;
    margin: auto;
    border-radius: 9999px;
    z-index: -10;
    background-image: conic-gradient(
        from 50deg at 80% 50%,
        transparent 0deg 15deg,
        rgba(235, 224, 230, 0.5) 20deg 30deg,
        transparent 50deg 60deg,
        rgba(235, 224, 230, 0.5) 90deg 100deg,
        transparent 120deg 360deg
    );
}
.ellipse_buttons_wrap::after {
    content: "";
    position: absolute;
    width: calc(100% / 3);
    height: 100%;
    margin: auto;
    border-radius: 9999px;
    z-index: -10;
    background-image: conic-gradient(
        from 220deg at 20% 50%,
        transparent 0deg 5deg,
        hsl(329, 20%, 90%) 20deg 25deg,
        transparent 40deg 360deg
    );
}
.center_radial {
    background: radial-gradient(circle at 50% 50%, #2d2a2d 20%, #030712 100%);
    position: relative;
}
.center_radial::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: radial-gradient(circle at 40% 60%, #faf4fa 2%, transparent 20%);
}
.center_radial::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: conic-gradient(
        from 200deg at 40% 60%,
        transparent 0deg 15deg,
        rgba(235, 224, 230, 0.5) 20deg 20deg,
        transparent 30deg 40deg,
        rgba(235, 224, 230, 0.5) 50deg 70deg,
        transparent 80deg 360deg
    );
}
</style>
<template>
    <div class="w-full">
        <div class="relative mb-36 h-96 w-full">
            <div
                class="absolute top-0 left-0 h-fit w-full min-w-full max-w-full overflow-hidden"
                id="timeline-wrapper"
                ref="timelineWrapper"
            ></div>
        </div>
        <!-- controller -->
        <div class="controller mx-auto mb-12 w-full max-w-7xl px-6">
            <div
                class="controller_body aspect-[20/7] w-full rounded-3xl bg-[#c20063]"
            >
                <div
                    class="absolute bottom-0 left-0 ml-0 h-full w-2/5 py-5 pl-5"
                >
                    <div class="h-full w-full rounded-2xl bg-[#f6e0af]"></div>
                </div>
                <div class="absolute bottom-0 h-3/5 w-full px-5 pb-5">
                    <div
                        class="controller_gold h-full w-full rounded-2xl bg-[#f6e0af]"
                    ></div>
                    <div
                        class="absolute bottom-1/3 left-0 z-30 flex h-4/5 w-1/3 flex-row"
                    >
                        <div class="relative z-30 mx-auto aspect-square h-full">
                            <div
                                class="absolute top-0 left-1/2 box-content aspect-square w-1/3 -translate-x-1/2 px-2 pt-2 after:absolute after:left-0 after:top-0 after:z-[-10] after:h-full after:w-full after:rounded-t-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="findNearHighRange"
                                    class="relative box-content aspect-square w-full rounded-t-md border-r-2 border-t-2 border-gray-600 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:bottom-0 after:left-0 after:h-2 after:w-full after:translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                        class="h-1/3 w-1/3 fill-[#e1dcd8] stroke-[#e1dcd8]"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM184 296c0 13.3 10.7 24 24 24s24-10.7 24-24V232h64c13.3 0 24-10.7 24-24s-10.7-24-24-24H232V120c0-13.3-10.7-24-24-24s-24 10.7-24 24v64H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h64v64z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                class="absolute top-1/2 left-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pr-2 after:absolute after:right-0 after:top-0 after:z-[-10] after:mr-px after:h-full after:w-full after:-translate-x-2 after:rounded-l-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="brushPrev"
                                    class="relative box-content aspect-square w-full rounded-l-md border-t-2 border-gray-600 bg-[#1d1d1d] after:absolute after:bottom-0 after:right-0 after:h-full after:w-2 after:translate-x-1/2 after:bg-[#1d1d1d]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 256 512"
                                        class="h-1/2 w-1/2 fill-[#e1dcd8] stroke-[#e1dcd8]"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                class="absolute top-1/2 right-1/2 z-30 flex aspect-square w-1/3 -translate-y-1/2 translate-x-1/2 items-center justify-center bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d]"
                            >
                                <div
                                    class="center_radial aspect-square w-2/3 rounded-full border-2 border-gray-900 shadow-md shadow-gray-900"
                                ></div>
                            </div>
                            <div
                                class="absolute top-1/2 right-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pl-1 after:absolute after:left-0 after:top-0 after:z-[-15] after:ml-2 after:h-full after:w-full after:translate-x-1 after:rounded-r-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="brushNext"
                                    class="relative box-content aspect-square w-full rounded-r-md border-t-2 border-r-2 border-gray-400 bg-[#515151] after:absolute after:bottom-0 after:left-0 after:h-full after:w-2 after:-translate-x-1/2 after:bg-[#515151]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 256 512"
                                        class="h-1/2 w-1/2 fill-[#e1dcd8] stroke-[#e1dcd8]"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                class="absolute bottom-0 right-1/2 box-content aspect-square w-1/3 translate-x-1/2 px-2 pb-1 after:absolute after:left-0 after:bottom-0 after:z-[-10] after:h-full after:w-full after:translate-y-1 after:rounded-b-xl after:bg-[#c20063]"
                            >
                                <button
                                    class="relative box-content aspect-square w-full rounded-b-md border-r-2 border-gray-200 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:top-0 after:left-0 after:h-2 after:w-full after:-translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
                                >
                                    <svg
                                        @click="findNearLowRange"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                        class="h-1/3 w-1/3 fill-[#e1dcd8] stroke-[#e1dcd8]"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM136 184c-13.3 0-24 10.7-24 24s10.7 24 24 24H280c13.3 0 24-10.7 24-24s-10.7-24-24-24H136z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- center buttons -->
                    <div
                        class="absolute inset-x-0 bottom-1/3 z-10 flex h-2/5 w-full flex-row py-2"
                    >
                        <div
                            class="ellipse_buttons_wrap z-[-3] mx-auto flex h-full w-1/3 items-center justify-around rounded-full border-4 border-[#c20063]"
                        >
                            <div
                                class="rounded_ellipse_wrap relative rounded-full border-4 border-[#75003b] bg-[#75003b] after:absolute after:top-0 after:right-0 after:z-[-2] after:-translate-y-[4px] after:translate-x-1/2 after:border-[#75003b] after:bg-[#75003b]"
                            >
                                <button
                                    @click="focusToggle"
                                    class="round_ellipse_button h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
                                >
                                    <span
                                        class="absolute left-1/2 bottom-0 w-full -translate-x-1/2 translate-y-full text-center"
                                        >Focus :
                                        {{ focus === true ? "ON" : "OFF" }}
                                    </span>
                                    Focus
                                </button>
                            </div>
                            <div
                                class="rounded_ellipse_wrap relative rounded-full border-4 border-[#75003b] bg-[#75003b] after:absolute after:top-0 after:left-0 after:z-[-2] after:-translate-y-[4px] after:-translate-x-1/2 after:border-[#75003b] after:bg-[#75003b]"
                            >
                                <button
                                    class="round_ellipse_button h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
                                >
                                    <span
                                        class="absolute left-1/2 bottom-0 w-full -translate-x-1/2 translate-y-full text-center"
                                        >Snap :
                                        {{ snap === true ? "ON" : "OFF" }}
                                    </span>
                                    Snap
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Add Remove roundButtons -->
                    <div
                        class="absolute bottom-1/3 right-0 z-30 mx-5 flex h-fit w-1/3 items-center justify-around px-10"
                    >
                        <div
                            class="aspect-square w-1/3 rounded-full bg-[#c20063] p-2"
                        >
                            <button
                                @click="undoOperate"
                                class="round_button relative aspect-square w-full rounded-full shadow-sm shadow-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                    class="h-1/3 w-1/3 fill-[#e1dcd8]"
                                >
                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="aspect-square w-1/3 rounded-full bg-[#c20063] p-2"
                        >
                            <button
                                class="round_button relative aspect-square w-full rounded-full shadow-sm shadow-gray-900"
                                @click="addTime(props.currentTime)"
                            >
                                <svg
                                    class="h-1/3 w-1/3 fill-[#e1dcd8]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- time dsiplay components -->
        <div class="mb-96 grid w-full gap-4 px-4 lg:grid-cols-2">
            <div
                v-for="(times, index) in timeSetArray"
                :key="index"
                class="my-2 w-full rounded-xl p-6"
                :style="
                    'background-color:' +
                    cols(index / timeSetArray.length) +
                    ';'
                "
            >
                <TimeInput
                    type="time"
                    step="1"
                    class="my-2 w-full"
                    v-model="timeSet[index * 2]"
                    required
                    @change="changeTime"
                ></TimeInput>
                <TimeInput
                    v-if="times.length !== 1"
                    type="time"
                    step="1"
                    class="my-2 w-full"
                    v-model="timeSet[index * 2 + 1]"
                    required
                    @change="changeTime"
                ></TimeInput>
            </div>
        </div>
        {{ timeSet }}
    </div>
</template>
