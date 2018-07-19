var width = 500,
    height = 500,
    radius = Math.min(width, height) / 2,
    innerRadius = 0 * radius; //radio circulo interno

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) {
        return d.width;
    });

var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([0, 0])
    .html(function(d) {
        var fechaFormateada = d3.timeFormat("%d-%m-%Y");
        return d.data.nombre + ": <span style='color:cyan'>" + d.data.avance + "</span>" + "% " + "<br>" + fechaFormateada(d.data.fecha_termino);
    });

var arc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(function(d) {
        return (radius - innerRadius) * (d.data.avance / 100.0) + innerRadius;
    });

var outlineArc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(radius);

var svg = d3.select("#grafico").append("svg")
    //.attr("width", width)
    //.attr("height", height)
    .attr('viewBox', "0 0 " + 500 + " " + 500)
    .style("border", '1px solid grey')
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

svg.call(tip);

function dibujarGrafico(entrada) {
    var data = entrada;
    try {
        data.forEach(function(d) {
            d.id = d.id;
            d.nombre = d.nombre;
            d.fecha_termino = d3.timeParse("%Y-%m-%d")(d.fecha_termino);
            d.avance = +d.avance;
            d.weight = 1;
            d.width = +d.weight;
        });
    } catch (TypeError) {
        document.getElementById("titulo").innerHTML = "Error al cargar datos";
    }
    if (data.length == 0) {
        document.getElementById("titulo").innerHTML = "No hay datos" + data + entrada;
        document.getElementById("zoom").setAttribute("hidden", true);
    } else {
        document.getElementById("nroTareas").innerHTML = "Numero de tareas: " + data.length;
    }

    var j = 0;
    var outerPath = svg.selectAll(".outlineArc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "parte")
        .attr("id", function() {
            while (j != data.length) {
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

    j = 0;
    var path = svg.selectAll(".solidArc").select(function() {
            while (j != data.length) {
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
        /*.attr("stroke", "grey")*/
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
    
    $("#zoom").anythingZoomer({
        clone: true,
        edit: true
    });
}

function actualizarGrafico(entrada) {
    var data = entrada;
    try {
        data.forEach(function(d) {
            d.id = d.id;
            d.nombre = d.nombre;
            d.fecha_termino = d3.timeParse("%Y-%m-%d")(d.fecha_termino);
            d.avance = +d.avance;
            d.weight = 1;
            d.width = +d.weight;
        });
    } catch (TypeError) {
        document.getElementById("titulo").innerHTML = "Error al cargar datos";
    }
    if (data.length == 0) {
        document.getElementById("titulo").innerHTML = "No hay datos" + data + entrada;
        document.getElementById("zoom").setAttribute("hidden", true);
    } else {
        document.getElementById("nroTareas").innerHTML = "Numero de tareas: " + data.length;
    }
    d3.select("svg").remove();
    var svg = d3.select("#grafico").append("svg")
    //.attr("width", width)
    //.attr("height", height)
    .attr('viewBox', "0 0 " + 500 + " " + 500)
    .style("border", '1px solid grey')
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var j = 0;
    var outerPath = svg.selectAll(".outlineArc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "parte")
        .attr("id", function() {
            while (j != data.length) {
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

    j = 0;
    var path = svg.selectAll(".solidArc").select(function() {
            while (j != data.length) {
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
        /*.attr("stroke", "grey")*/
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
}

function calcularColor(d) {
    var hoy = new Date();
    var fechaActividad = new Date(d.data.fecha_termino);
    var diferencia = daysBetween(hoy, fechaActividad);

    if (diferencia <= 0) {
        return "#cc0000"; //rojo    
    }
    if (diferencia < 7 && diferencia > 0) {
        return "#ff9900"; //naranjo    
    }
    if (diferencia >= 7) {
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
$("#opcion").change(function() {
    var proyectoid = $(this).attr("data-id");
    var areaid = $(this).val();
    var ruta = '/grafico/' + proyectoid + '/filtrar';
    var datos = {
        "proyectoid": proyectoid,
        "areaid": areaid
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })
    $.ajax({
        method: 'POST', // Type of response and matches what we said in the route
        url: ruta, // This is the url we gave in the route
        data: datos, // la información a enviar (también es posible utilizar una cadena de datos)
        dataType: 'json', //tipo de respuesta esperada
        success: function(response) { // What to do if we succeed            
            actualizarGrafico(response);
        },
        error: function(jqXHR, textStatus, errorThrown, exception) { // What to do if we fail            
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
})

$("#activar").click(function() {
    var estado = $("#activar").val(); // 0 o 1   
    var zoomer = $("#zoom").data('zoomer');
    if (estado == 1) {
        zoomer.setEnabled(false);
        $(this).val(0);
    }
    if (estado == 0) {
        zoomer.setEnabled(true);
        $(this).val(1);
    }
});
