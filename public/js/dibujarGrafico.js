var width = 600,
    height = 600,
    radius = Math.min(width, height) / 2,
    innerRadius = 0 * radius; //radio circulo interno

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) {
        return d.width;
    });

var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([0,0])
    .html(function(d) {
        return d.data.nombre + ": <span style='color:cyan'>" + d.data.avance + "</span>"+"%";
    });

var arc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(function(d) {
        return (radius - innerRadius) * (d.data.avance / 100.0) + innerRadius;
    });

var outlineArc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(radius);

var svg = d3.select("#grafico")
    .append("svg")    
    .attr("width", width)
    .attr("height", height)
    .style("border", '1px solid black')
    .call(d3.behavior.zoom().on("zoom", function () {
    svg.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")")
    }))
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

svg.call(tip);

var entrada = variablesjs.tarea;
var data = JSON.parse(entrada);
try{
    data.forEach(function(d) {    
    d.id = d.id;
    d.nombre = d.nombre;                             
    d.fechatermino = d3.timeParse("%Y-%m-%d")(d.fechatermino);    
    d.avance = +d.avance;   
    d.weight = 1;
    d.width = +d.weight;         
});
}
catch(TypeError){
    document.getElementById("titulo").innerHTML = "No hay datos";    
}

var j=0;
var outerPath = svg.selectAll(".outlineArc")
    .data(pie(data))
    .enter().append("g")
    .attr("class", "parte")
    .attr("id", function(){
        while(j!=data.length){
            j++;
            var string = "parte";
            string = string.concat(j.toString());
            return string;
        }        
    })
    .append("path")
    .attr("fill", calcularColor)
    .attr("stroke", "grey")
    .attr("class", "outlineArc")
    .attr("d", outlineArc)
    .on('mouseover', tip.show)
    .on('mouseout', tip.hide);
     

var path = svg.selectAll(".solidArc").select(function(){
        while(j!=data.length){
            j++;
            var string = "parte";
            string = string.concat(j.toString());
            return string;
        }
    })
    .data(pie(data))
    .enter()
    .append("path")
    .attr("fill", "#074590")
    .attr("class", "solidArc")
    .attr("stroke", "grey")
    .attr("d", arc)
    .on('mouseover', tip.show)
    .on('mouseout', tip.hide);

// calculate the weighted mean avance
var avance = 
data.reduce(function(a, b) {      
    return a + (b.avance * b.weight);
}, 0) /
data.reduce(function(a, b) {
    return a + b.weight;
}, 0);

function calcularColor(d){  
  var hoy = new Date();  
  var fechaActividad = new Date(d.data.fechatermino);  
  var diferencia = daysBetween(hoy, fechaActividad);   
  console.log(diferencia);
  if(diferencia <= 0){
    console.log("rojo");  
    return "#cc0000"; //rojo    
  }
  if(diferencia < 7 && diferencia > 0){
    console.log("naranjo");
    return "#ff9900"; //naranjo    
  }
  if(diferencia >= 7){
    console.log("verde");
    return "#009900"; //verde
  }

}

function treatAsUTC(date) {
    var result = new Date(date);
    result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
    return result;
}

function daysBetween(startDate, endDate) {
    var millisecondsPerDay = 24 * 60 * 60 * 1000;
    return (treatAsUTC(endDate) - treatAsUTC(startDate)) / millisecondsPerDay;
}

function resetZoom(){    
    svg.attr("transform", "translate(0,0) scale(1)");
}
