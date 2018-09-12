var width = 500,
    height = 500,
    radius = Math.min(width, height) / 2,
    innerRadius = 0.10 * radius; //radio circulo interno

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) {
        return d.width;
    });

var formatoFecha = d3.timeFormat("%d-%m-%Y");
/*var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([0, 0])
    .html(function(d) {
        var fechaFormateada = new Date(d.data.fecha_termino.date);        
        return d.data.nombre + ": <span style='color:cyan'>" + d.data.avance + "</span>" + "% " + "<br>" + formatoFecha(fechaFormateada);
    });*/

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
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

function dibujarGrafico(datos) {
    console.log(datos);
    var data = datos;
    try {
        data.forEach(function(d) {
            d.id = d.id;
            d.proyecto_id = d.proyecto_id;
            d.area_id = d.area_id;
            d.nombre = d.nombre;
            d.fecha_inicio.date = d.fecha_inicio.date;
            d.fecha_termino_original.date = d.fecha_termino_original.date;
            d.fecha_termino.date = d.fecha_termino.date;
            d.atraso = d.atraso;
            d.avance = d.avance;
            d.observaciones = d.observaciones;
            d.weight = 1;
            d.width = +d.weight;
        });
    } catch (TypeError) {
        document.getElementById("titulo").innerHTML = "Error al cargar datos";
    }

    var outerPath = svg.selectAll(".outlineArc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "parte")
        /*.on('mouseover', tip.show)
        .on('mouseout', tip.hide)*/
        .on('click', function(d, i) {            
            $("#modal").modal();
        })
        .on('mouseover', function(d, i) {
            //console.log("You clicked", d), i;
            $(".detallesTarea").show();
            $("#nombre").text(d.data.nombre);
            $("#area").text(d.data.nombreArea);
            $("#fir").text(formatoFecha(new Date(d.data.fecha_inicio.date)));
            $("#ftro").text(formatoFecha(new Date(d.data.fecha_termino_original.date)));
            if(d.data.fecha_termino.date == d.data.fecha_termino_original.date){                
                $("#ftrm").text("-");
                $("#atraso").text("-");
            }else{                
                $("#ftrm").text(formatoFecha(new Date(d.data.fecha_termino.date)));
                $("#atraso").text(d.data.atraso);
            }  
            $("#avance").text(d.data.avance);
            $("#observaciones").text(d.data.observaciones);
        });

    outerPath.append("path")
        .attr("fill", calcularColor)
        .attr("class", "outlineArc")
        /*.attr("stroke", "grey")*/
        .attr("d", outlineArc);

    outerPath.append("path")
        .attr("fill", "#074590")
        .attr("class", "solidArc")
        /*.attr("stroke", "grey")*/
        .attr("d", arc);

    // calculate the weighted mean avance
    var avance =
        data.reduce(function(a, b) {
            return a + (b.avance * b.weight);
        }, 0) /
        data.reduce(function(a, b) {
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

function actualizarGrafico(entrada) {
    var data = entrada;
    try {
        data.forEach(function(d) {
            d.id = d.id;
            d.proyecto_id = d.proyecto_id;
            d.area_id = d.area_id;
            d.nombre = d.nombre;
            d.fecha_inicio.date = d.fecha_inicio.date;
            d.fecha_termino_original.date = d.fecha_termino_original.date;
            d.fecha_termino.date = d.fecha_termino.date;
            d.atraso = d.atraso;
            d.avance = d.avance;
            d.observaciones = d.observaciones
            d.weight = 1;
            d.width = +d.weight;
        });
    } catch (TypeError) {
        document.getElementById("titulo").innerHTML = "Error al cargar datos";
    }
    d3.select("svg").remove();
    var svg = d3.select("#grafico").append("svg")
        //.attr("width", width)
        //.attr("height", height)
        .attr('viewBox', "0 0 " + 500 + " " + 500)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var outerPath = svg.selectAll(".outlineArc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "parte")
        .on('mouseover', function(d, i) {
            //console.log("You clicked", d), i;
            $(".detallesTarea").show();
            $("#nombre").text(d.data.nombre);
            $("#area").text(d.data.nombreArea);
            $("#fir").text(formatoFecha(new Date(d.data.fecha_inicio.date)));
            $("#ftro").text(formatoFecha(new Date(d.data.fecha_termino_original.date)));
            if(d.data.fecha_termino.date == d.data.fecha_termino_original.date){
                $("#ftrm").text("-");
                $("#atraso").text("-");
            }else{
                $("#ftrm").text(formatoFecha(new Date(d.data.fecha_termino.date)));
                $("#atraso").text(d.data.atraso);
            }            
            $("#avance").text(d.data.avance);
            $("#observaciones").text(d.data.observaciones);
        });
    /*.on('mouseover', tip.show)
    .on('mouseout', tip.hide);*/

    outerPath.append("path")
        .attr("fill", calcularColor)
        .attr("class", "outlineArc")
        /*.attr("stroke", "grey")*/
        .attr("d", outlineArc);

    outerPath.append("path")
        .attr("fill", "#074590")
        .attr("class", "solidArc")
        /*.attr("stroke", "grey")*/
        .attr("d", arc);

    // calculate the weighted mean avance
    avance =
        data.reduce(function(a, b) {
            return a + (b.avance * b.weight);
        }, 0) /
        data.reduce(function(a, b) {
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

function calcularColor(d) {    
    if (d.data.colorAtraso == "VERDE") {
        return "#28a745"; //verde
    } else if (d.data.colorAtraso == "AMARILLO") {        
        return "#ffff00"; //amarillo
    } else if (d.data.colorAtraso == "NARANJO") {
        return "#f48024"; //naranjo
    } else if (d.data.colorAtraso == "ROJO"){
        return "#dc3545"; //rojo
    }else{
        return "#000000";
    }
}

function habilitarZoom() {
    /*
    $("#zoom").anythingZoomer({
        clone: true,
        switchEvent : ''
    });
    */
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
            $("#detallesTarea").hide();
            actualizarGrafico(response);
            habilitarZoom();            
        },
        error: function(jqXHR, textStatus, errorThrown, exception) { // What to do if we fail            
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
})
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
