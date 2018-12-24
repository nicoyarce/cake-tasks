var width = 500,
    height = 500,
    radius = Math.min(width, height) / 2,
    innerRadius = 0.12 * radius; //radio circulo interno

var pie = d3.layout.pie()    
    .value(function(d) { return d.width; })
    .sort(null);

var arc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(function(d) {
        return (radius - innerRadius) * (d.data.avance / 100.0) + innerRadius;
    });

var outlineArc = d3.svg.arc()
    .innerRadius(innerRadius)
    .outerRadius(radius);

var svgSimbologia = d3.select('#simbologia')
    .attr('viewBox', "0,0,150,53");

var formatoFecha = d3.timeFormat("%d-%m-%Y");
/*var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([0, 0])
    .html(function(d) {
        var fechaFormateada = new Date(d.data.fecha_termino.date);        
        return d.data.nombre + ": <span style='color:cyan'>" + d.data.avance + "</span>" + "% " + "<br>" + formatoFecha(fechaFormateada);
    });*/
function dibujarGrafico(data) {
    var svg = d3.select("#grafico").append("svg")
    //.attr("width", width)
    //.attr("height", height)
    .attr('viewBox', "-37 -37 " + 570 + " " + 570)
    .attr("class", "grafico")
    .append("g")
    .attr("transform", "translate(" + (width / 2) + "," + (height / 2) + ")");   
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
        .on('mouseout', function (d, i){
            $(".detallesTarea").hide();
            $("#critica").hide();
            svgSimbologia.selectAll("line.flecha").remove();
        })
        .on('click', function(d, i) {                    
            cargarVistaGantt(d.data.id);         
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
            if(d.data.critica == 1){
                $("#critica").show();
            }
            else{
                $("#critica").hide();
            }
            dibujarFlecha(d.data.porcentajeAtraso);
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

    outerPath.append("svg:text")                             //add a label to each slice
        .attr("transform", function(d) {                    //set the label's origin to the center of the arc
        //we have to make sure to set these before calling arc.centroid
        d.innerRadius = 0;
        d.outerRadius = radius;
        var c = outlineArc.centroid(d)
        return "translate(" + c[0]*1.92 +"," + c[1]*1.92 + ")";        //this gives us a pair of coordinates like [50, 50]
    })
    .attr("text-anchor", "middle")                          //center the text on it's origin
    .text(function (d, i){
        if(d.data.critica == 1){
            return i+1;
        }
        else{
            return "";
        }
    });        //get the label from our original data array
        
    

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

/*
function habilitarZoom() {    
    $("#zoom").anythingZoomer({
        clone: true,
        switchEvent : ''
    });    
}
*/

function cargarVistaGantt(id_tarea){       
    var proyectoid = $("#opcion").attr("data-id");
    var ruta = '/visor';
    var datos = {
        "proyectoid": proyectoid,
        "tareaid": id_tarea,        
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })    
    $.post(ruta, datos, function(html){
        var w = window.open('about:blank');
        w.document.open();
        w.document.write(html);
        w.document.close();
    });
}

$(".form-control").change(function() {
    //console.log("Cambio en combobox")
    var proyectoid = $(this).attr("data-id");
    var opcionArea = $("#opcionArea").val();
    var opcionColor = $("#opcionColor").val();
    var ruta = '/grafico/' + proyectoid + '/filtrar';
    var datos = {
        "proyectoid": proyectoid,
        "areaid": opcionArea,
        "colorAtraso": opcionColor
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
            d3.selectAll("svg.grafico").remove();
            dibujarGrafico(response);
            //habilitarZoom();            
        },
        error: function(jqXHR, textStatus, errorThrown, exception) { // What to do if we fail            
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
})
/*
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
var borde = 10;
var bordeArriba = 20;
var alturaBarra = 10;
var largoFlecha = 25;
var colorBorde = "black";
var grosorLineaNegra = 0.4;
function dibujarSimbologia(dato){    
    /*---LINEAS---*/
    var lineaVertIzq = svgSimbologia.append("line")
        .attr("x1",borde)  
        .attr("y1",bordeArriba)  
        .attr("x2",borde)  
        .attr("y2",borde)
        .attr("stroke",colorBorde)  
        .attr("stroke-width",grosorLineaNegra);
    var lineaHorizIzq = svgSimbologia.append("line")
        .attr("x1",borde)  
        .attr("y1",(bordeArriba+borde)/2)  
        .attr("x2",30+borde)  
        .attr("y2",(bordeArriba+borde)/2)
        .attr("stroke","black")  
        .attr("stroke-width",grosorLineaNegra);
    var lineaHorizDer = svgSimbologia.append("line")
        .attr("x1",70+borde)  
        .attr("y1",(bordeArriba+borde)/2)  
        .attr("x2",100+borde)  
        .attr("y2",(bordeArriba+borde)/2)
        .attr("stroke","black")  
        .attr("stroke-width",grosorLineaNegra);
    var lineaVertDer = svgSimbologia.append("line")
        .attr("x1",100+borde)  
        .attr("y1",bordeArriba)  
        .attr("x2",100+borde)  
        .attr("y2",borde)
        .attr("stroke","black")  
        .attr("stroke-width",grosorLineaNegra);
    var lineaFinal1 = svgSimbologia.append("line")
        .attr("x1",130)  
        .attr("y1",bordeArriba+5)  
        .attr("x2",140)  
        .attr("y2",bordeArriba+(alturaBarra/2)+5)
        .attr("stroke",colorBorde)  
        .attr("stroke-width",grosorLineaNegra);
    var lineaFinal2 = svgSimbologia.append("line")
        .attr("x1",130)  
        .attr("y1",bordeArriba+alturaBarra+5)  
        .attr("x2",140)  
        .attr("y2",bordeArriba+alturaBarra-(alturaBarra/2)+5)
        .attr("stroke",colorBorde)  
        .attr("stroke-width",grosorLineaNegra);

    /*---TEXTO---*/
    var fit = svgSimbologia.append("text")
        .attr("x",borde)
        .attr("y",borde/2)
        .text("F.I.T.")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    var tiempo = svgSimbologia.append("text")
        .attr("x",50+borde)
        .attr("y",(bordeArriba+borde)/2)
        .text("Tiempo")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    var ftt = svgSimbologia.append("text")
        .attr("x",100+borde)
        .attr("y",borde/2)
        .text("F.T.T.")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "6px")
        .attr("fill", "black");
    var cero = svgSimbologia.append("text")
        .attr("x",borde)
        .attr("y",bordeArriba+alturaBarra+10)
        .text("0")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    var sesenta = svgSimbologia.append("text")
        .attr("x",60+borde)
        .attr("y",bordeArriba+alturaBarra+10)
        .text("0,6")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    var noventa = svgSimbologia.append("text")
        .attr("x",90+borde)
        .attr("y",bordeArriba+alturaBarra+10)
        .text("0,9")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");
    var cien = svgSimbologia.append("text")
        .attr("x",100+borde)
        .attr("y",bordeArriba+alturaBarra+10)
        .text("1")
        .attr("text-anchor", "middle")
        .attr("font-family", "sans-serif")
        .attr("font-size", "4px")
        .attr("fill", "black");

    /*---BARRA---*/ 
    var rectangulo1 = svgSimbologia.append("rect")
        .attr("fill", "#28a745") //verde
        .attr("x",borde)
        .attr("y",bordeArriba+5)
        .attr("width",60)
        .attr("height",alturaBarra)
        .attr("stroke",colorBorde)
        .attr("stroke-width",grosorLineaNegra);
    var rectangulo2 = svgSimbologia.append("rect")
        .attr("fill", "#ffff00") //amarillo
        .attr("x",60+borde)
        .attr("y",bordeArriba+5)
        .attr("width",30)
        .attr("height",alturaBarra)
        .attr("stroke",colorBorde)
        .attr("stroke-width",grosorLineaNegra);
    var rectangulo3 = svgSimbologia.append("rect")
        .attr("fill", "#f48024") //naranjo
        .attr("x",90+borde)
        .attr("y",bordeArriba+5)
        .attr("width",10)
        .attr("height",alturaBarra)
        .attr("stroke",colorBorde)
        .attr("stroke-width",grosorLineaNegra);
    var rectangulo4 = svgSimbologia.append("rect")
        .attr("fill", "#dc3545") //rojo
        .attr("x",100+borde)
        .attr("y",bordeArriba+5)
        .attr("width",20)
        .attr("height",alturaBarra)
        .attr("stroke",colorBorde)
        .attr("stroke-width",grosorLineaNegra);    
}

function dibujarFlecha(avance){   
    /*---FLECHA---*/
    //console.log(avance);
    var line = svgSimbologia.append("line")
        //mover coordenadas x para avance  //278 100%        
        .attr("x1",avance+borde)  
        .attr("y1",bordeArriba+alturaBarra+largoFlecha+5)  
        .attr("x2",avance+borde)  
        .attr("y2",bordeArriba+alturaBarra+15)
        .attr("class", "flecha")
        .attr("stroke","black")  
        .attr("stroke-width",0.9)  
        .attr("marker-end","url(#arrow)");    
}


