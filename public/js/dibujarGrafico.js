var width = 500,
    height = 500,
    radius = Math.min(width, height) / 2,
    innerRadius = 0.12 * radius; //radio circulo interno

var pie = d3.layout
    .pie()
    .value(function (d) {
        return d.width;
    })
    .sort(null);

var arc = d3.svg
    .arc()
    .innerRadius(innerRadius)
    .outerRadius(function (d) {
        return (radius - innerRadius) * (d.data.avance / 100.0) + innerRadius;
    });

var outlineArc = d3.svg.arc().innerRadius(innerRadius).outerRadius(radius);
var svgSimbologia = d3.select("#simbologia").attr("viewBox", "0,0,150,53");
var formatoFecha = d3.timeFormat("%d-%m-%Y");
/*var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([0, 0])
    .html(function(d) {
        var fechaFormateada = new Date(d.data.fecha_termino.date);
        return d.data.nombre + ": <span style='color:cyan'>" + d.data.avance + "</span>" + "% " + "<br>" + formatoFecha(fechaFormateada);
    });*/
function dibujarGrafico(data) {
    var svg = d3
        .select("#grafico")
        .append("svg")
        //.attr("width", width)
        //.attr("height", height)
        .attr("viewBox", "-37 -37 " + 570 + " " + 570)
        .attr("class", "grafico")
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
    try {
        data.forEach(function (d) {
            d.id = d.id;
            d.weight = 1;
            d.width = +d.weight;
        });
    } catch (TypeError) {
        document.getElementById("titulo").innerHTML = "Error al cargar datos";
    }
    var outerPath = svg
        .selectAll(".outlineArc")
        .data(pie(data))
        .enter()
        .append("g")
        .attr("class", "parte")
        .on("click", function (d, i) {
            //$(".detallesTarea").hide();
            $("#listaObservaciones").hide();
            $("#critica").hide();
            $("#listaObservaciones").empty();
            svgSimbologia.selectAll("line.flecha").remove();
            muestraDatosGrafico(d, i);
            d3.selectAll(".outlineArc")
                .style("stroke-width", "0")
                .style("stroke", "cyan");
            d3.selectAll(".solidArc")
                .style("stroke-width", "0")
                .style("stroke", "cyan");
            d3.select(this)
                .select(".outlineArc")
                .style("stroke-width", "3")
                .style("stroke", "cyan");
            d3.select(this)
                .select(".solidArc")
                .style("stroke-width", "3")
                .style("stroke", "cyan");
        })
        .on("dblclick", function (d, i) {
            cargarVistaGantt(d.data.id);
        });

    outerPath
        .append("path")
        .attr("fill", calcularColor)
        .attr("class", "outlineArc")
        .attr("d", outlineArc);

    outerPath
        .append("path")
        .attr("fill", "#074590")
        .attr("class", "solidArc")
        .attr("d", arc);

    outerPath
        .append("svg:text") //add a label to each slice
        .attr("transform", function (d) {
            //set the label's origin to the center of the arc
            //we have to make sure to set these before calling arc.centroid
            d.innerRadius = 0;
            d.outerRadius = radius;
            var c = outlineArc.centroid(d);
            return "translate(" + c[0] * 1.92 + "," + c[1] * 1.92 + ")"; //this gives us a pair of coordinates like [50, 50]
        })
        .attr("text-anchor", "middle") //center the text on it's origin
        .text(function (d, i) {
            if (d.data.critica == 1) {
                return i + 1;
            } else {
                return "";
            }
        }); //get the label from our original data array

    // calculate the weighted mean avance
    var avance =
        data.reduce(function (a, b) {
            return a + b.avance * b.weight;
        }, 0) /
        data.reduce(function (a, b) {
            return a + b.weight;
        }, 0);

    //svg.call(tip);

    if (data.length == 0) {
        svg.append("svg:text")
            .attr("class", "nroTareas")
            .attr("dy", ".35em")
            .attr("text-anchor", "middle") // text-align: right
            .text("No hay datos");
    } else {
        svg.append("svg:text")
            .attr("class", "nroTareas")
            .attr("dy", ".35em")
            .attr("text-anchor", "middle") // text-align: right
            .text(data.length);
    }
}

function muestraDatosGrafico(d, i) {
    //console.log("You clicked", d), i;
    $(".detallesTarea").show();
    $("#nombre").text(d.data.nombre);
    $("#area").text(d.data.nombreArea);
    $("#fir").text(formatoFecha(new Date(d.data.fecha_inicio)));
    $("#ftro").text(formatoFecha(new Date(d.data.fecha_termino_original)));
    if (d.data.fecha_termino == d.data.fecha_termino_original) {
        $("#ftrm").text("-");
        $("#atraso").text("-");
    } else {
        $("#ftrm").text(formatoFecha(new Date(d.data.fecha_termino)));
        $("#atraso").text(d.data.atraso);
    }
    $("#avance").text(d.data.avance + "% - " + d.data.glosaAvance);
    if (d.data.observaciones.length > 0) {
        $("#listaObservaciones").show();
    } else {
        $("#listaObservaciones").hide();
    }
    $.each(d.data.observaciones, function (indice) {
        string =
            d.data.observaciones[indice].contenido +
            " - " +
            formatoFecha(new Date(d.data.observaciones[indice].created_at)) +
            " - " +
            d.data.observaciones[indice].autor[0];
        $('<div class="list-group-item flex-column align-items-start"></div>')
            .appendTo("#listaObservaciones")
            .text(string);
    });
    if (d.data.critica == 1) {
        $("#critica").show();
    } else {
        $("#critica").hide();
    }
    if (d.data.trabajo_externo == 1) {
        $("#trabajo_externo").show();
    } else {
        $("#trabajo_externo").hide();
    }
    dibujarFlecha(d.data.porcentajeAtraso);
}

function calcularColor(d) {
    if (d.data.colorAtraso != "") {
        return d.data.colorAtraso;
    } else {
        return "#000000";
    }
}

/*
function habilitarZoom() {
    $("#zoom").anythingZoomer({
        clone: true,
        switchEvent : ''
    });
}
*/

function cargarVistaGantt(id_tarea) {
    var proyectoid = $("#opcion").attr("data-id");
    var ruta = "/visor";
    var datos = {
        proyectoid: proyectoid,
        tareaid: id_tarea,
    };
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val(),
        },
    });
    $.post(ruta, datos, function (html) {
        var w = window.open("about:blank");
        w.document.open();
        w.document.write(html);
        w.document.close();
    });
}

/* Codigo Zoom
var primeraVez = true;
$("#activar").click(function() {
    var estado = $("#activar").val(); // 0 o 1
    var zoomer = $("#zoom").data('zoomer');
    if (estado == 1) {
        console.log("Desactivado")
        zoomer.setEnabled(false);
        $("#botonZoom").text("Activar zoom");
        $(this).val(0);
    }
    if (estado == 0 && primeraVez) {
        console.log("Activado por primera vez")
        habilitarZoom();
        primeraVez = false;
        $("#botonZoom").text("Desactivar zoom");
        $(this).val(1);
    } else if (estado == 0) {
        console.log("Activado")
        zoomer.setEnabled(true);
        $("#botonZoom").text("Desactivar zoom");
        $(this).val(1);
    }
});
*/
const borde = 10;
const bordeArriba = 20;
const alturaBarra = 10;
const largoFlecha = 25;
const colorBorde = "black";
const grosorLineaNegra = 0.4;

function dibujarSimbologia(propiedades) {
    /*---LINEAS---*/
    const lineaVertIzq = svgSimbologia
        .append("line")
        .attr("x1", borde)
        .attr("y1", bordeArriba)
        .attr("x2", borde)
        .attr("y2", borde)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
    const lineaHorizIzq = svgSimbologia
        .append("line")
        .attr("x1", borde)
        .attr("y1", (bordeArriba + borde) / 2)
        .attr("x2", 30 + borde)
        .attr("y2", (bordeArriba + borde) / 2)
        .attr("stroke", "black")
        .attr("stroke-width", grosorLineaNegra);
    const lineaHorizDer = svgSimbologia
        .append("line")
        .attr("x1", 70 + borde)
        .attr("y1", (bordeArriba + borde) / 2)
        .attr("x2", 100 + borde)
        .attr("y2", (bordeArriba + borde) / 2)
        .attr("stroke", "black")
        .attr("stroke-width", grosorLineaNegra);
    const lineaVertDer = svgSimbologia
        .append("line")
        .attr("x1", 100 + borde)
        .attr("y1", bordeArriba)
        .attr("x2", 100 + borde)
        .attr("y2", borde)
        .attr("stroke", "black")
        .attr("stroke-width", grosorLineaNegra);
    const lineaFinal1 = svgSimbologia
        .append("line")
        .attr("x1", 130)
        .attr("y1", bordeArriba + 5)
        .attr("x2", 140)
        .attr("y2", bordeArriba + alturaBarra / 2 + 5)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
    const lineaFinal2 = svgSimbologia
        .append("line")
        .attr("x1", 130)
        .attr("y1", bordeArriba + alturaBarra + 5)
        .attr("x2", 140)
        .attr("y2", bordeArriba + alturaBarra - alturaBarra / 2 + 5)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);

    /*---TEXTO---*/
    const fit = svgSimbologia
        .append("text")
        .attr("x", borde)
        .attr("y", borde / 2)
        .text("F.I.T.")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    const tiempo = svgSimbologia
        .append("text")
        .attr("x", 50 + borde)
        .attr("y", (bordeArriba + borde) / 2)
        .text("Tiempo")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    const ftt = svgSimbologia
        .append("text")
        .attr("x", 100 + borde)
        .attr("y", borde / 2)
        .text("F.T.T.")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    const cero = svgSimbologia
        .append("text")
        .attr("x", borde)
        .attr("y", bordeArriba + alturaBarra + 10)
        .text("0")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    const sesenta = svgSimbologia
        .append("text")
        .attr("x", 60 + borde)
        .attr("y", bordeArriba + alturaBarra + 10)
        .text("0,6")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    const noventa = svgSimbologia
        .append("text")
        .attr("x", 90 + borde)
        .attr("y", bordeArriba + alturaBarra + 10)
        .text("0,9")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    const cien = svgSimbologia
        .append("text")
        .attr("x", 100 + borde)
        .attr("y", bordeArriba + alturaBarra + 10)
        .text("1")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    const rectangulo1 = svgSimbologia
        .append("rect")
        .attr("fill", propiedades[0]["color"]) //verde
        .attr("x", borde)
        .attr("y", bordeArriba + 5)
        .attr("width", 60)
        .attr("height", alturaBarra)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
    const rectangulo2 = svgSimbologia
        .append("rect")
        .attr("fill", propiedades[1]["color"]) //amarillo
        .attr("x", 60 + borde)
        .attr("y", bordeArriba + 5)
        .attr("width", 30)
        .attr("height", alturaBarra)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
    const rectangulo3 = svgSimbologia
        .append("rect")
        .attr("fill", propiedades[2]["color"]) //naranjo
        .attr("x", 90 + borde)
        .attr("y", bordeArriba + 5)
        .attr("width", 10)
        .attr("height", alturaBarra)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
    const rectangulo4 = svgSimbologia
        .append("rect")
        .attr("fill", propiedades[3]["color"]) //rojo
        .attr("x", 100 + borde)
        .attr("y", bordeArriba + 5)
        .attr("width", 20)
        .attr("height", alturaBarra)
        .attr("stroke", colorBorde)
        .attr("stroke-width", grosorLineaNegra);
}

function dibujarFlecha(avance) {
    /*---FLECHA---*/
    //console.log(avance);
    const line = svgSimbologia
        .append("line")
        //mover coordenadas x para avance  //278 100%
        .attr("x1", avance + borde)
        .attr("y1", bordeArriba + alturaBarra + largoFlecha + 5)
        .attr("x2", avance + borde)
        .attr("y2", bordeArriba + alturaBarra + 15)
        .attr("class", "flecha")
        .attr("stroke", "black")
        .attr("stroke-width", 0.9)
        .attr("marker-end", "url(#arrow)");
}
