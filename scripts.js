window.onload = function () {
    /* Añadir eventListener */
    document.getElementById("imgBano").addEventListener("click", cambiarPresupuestoBano);
    document.getElementById("imgTejado").addEventListener("click", cambiarPresupuestoTejado);
    document.getElementById("imgCocina").addEventListener("click", cambiarPresupuestoCocina);
    document.getElementById("imgFontaneria").addEventListener("click", cambiarPresupuestoFontaneria);
    document.getElementById("bano").classList.add("oculto");
    document.getElementById("cocina").classList.add("oculto");
    document.getElementById("tejado").classList.add("oculto");
    document.getElementById("fontaneria").classList.add("oculto");

    document.getElementById("bCalcularBano").addEventListener("click", calcularBano);
    document.getElementById("bCalcularCocina").addEventListener("click", calcularCocina);
    document.getElementById("bCalcularTejado").addEventListener("click", calcularTejado);
    document.getElementById("bCalcularFont").addEventListener("click", calcularFont);
}

/* Funciones para mostrar la zona seleccionada */
function cambiarPresupuestoBano() {
    document.getElementById("seleccion").classList.add("oculto");
    document.getElementById("bano").classList.remove("oculto");
}

function cambiarPresupuestoTejado() {
    document.getElementById("seleccion").classList.add("oculto");
    document.getElementById("tejado").classList.remove("oculto");
}

function cambiarPresupuestoCocina() {
    document.getElementById("seleccion").classList.add("oculto");
    document.getElementById("cocina").classList.remove("oculto");
}

function cambiarPresupuestoFontaneria() {
    document.getElementById("seleccion").classList.add("oculto");
    document.getElementById("fontaneria").classList.remove("oculto");
}

/* Funciones para calcular el presupuesto */
function calcularBano() {
    let total = 0;
    let result = document.getElementById("resultadoBano");

    const pDucha = document.getElementById("pDucha");
    const cDucha = document.getElementById("cDucha");
    const lavabo = document.getElementById("lavabo");
    const azulejo = document.getElementById("azulejo");
    const m2azulejo = document.getElementById("m2azulejo").value;

    if (pDucha.checked) {
        total += 300;
    }
    if (cDucha.checked) {
        total += 200;
    }
    if (lavabo.checked) {
        total += 150;
    }
    if (azulejo.checked) {
        total += m2azulejo * 45;
    }

    if (m2azulejo != "" && !azulejo.checked) {
        result.innerHTML = "Por favor, seleccione el check de azulejo para elegir los metros cuadrados";
    } else if (m2azulejo == "" && azulejo.checked) {
        result.innerHTML = "Por favor, seleccione una cantidad de metros cuadrados de azulejo";
    } else if (isNaN(m2azulejo)){
        result.innerHTML = "Por favor, introduce un número en la cantidad de metros cuadrados de azulejo";
    } else {
        result.innerHTML = "El presupuesto total es " + total + "€";
    }
}

function calcularCocina(){
    let total = 0;
    let result = document.getElementById("resultadoCocina");

    const fregadero = document.getElementById("fregadero");
    const cExtractora = document.getElementById("cExtractora");
    const encimera = document.getElementById("encimera");
    const matEncimera = document.querySelector('input[name="matEncimera"]:checked').value;
    const m2encimera = document.getElementById("m2encimera").value;
    let valorMaterial;

    if(matEncimera == "granito"){
        valorMaterial = 200;
    } else if (matEncimera == "marmol") {
        valorMaterial = 230;
    } else if (matEncimera == "basalto") {
        valorMaterial = 300;
    }

    if(fregadero.checked){
        total += 300;
    }
    if(cExtractora.checked){
        total += 200
    }
    if(encimera.checked){
        total += m2encimera * valorMaterial;
    }

    if (m2encimera != "" && !encimera.checked) {
        result.innerHTML = "Por favor, seleccione el check de encimera para elegir los metros cuadrados";
    } else if (m2encimera == "" && encimera.checked) {
        result.innerHTML = "Por favor, seleccione una cantidad de metros cuadrados de encimera";
    } else if (isNaN(m2encimera)){
        result.innerHTML = "Por favor, introduce un número en la cantidad de metros cuadrados de encimera";
    } else {
        result.innerHTML = "El presupuesto total es " + total + "€";
    }
}

function calcularTejado() {
    let total = 0;
    let result = document.getElementById("resultadoTejado");

    const retiradaTejado = document.getElementById("retiradaTejado");
    const impermeabilizacion = document.getElementById("impermeabilizacion");
    const aislante = document.getElementById("aislante");
    const cubierta = document.getElementById("cubierta");
    const matCubierta = document.querySelector('input[name="materialCubierta"]:checked').value;
    const m2tejado = document.getElementById("m2tejado").value;
    let valorMaterial;

    if(matCubierta == "teja") {
        valorMaterial = 40;
    } else if (matCubierta == "pizarra") {
        valorMaterial = 55;
    } else if (matCubierta == "panel") {
        valorMaterial = 70;
    }

    if(retiradaTejado.checked){
        total += 500;
    }
    if(impermeabilizacion.checked){
        total += 300;
    }
    if(aislante.checked){
        total += 400;
    }
    if(cubierta.checked){
        total += m2tejado * valorMaterial;
    }

    if (m2tejado != "" && !cubierta.checked) {
        result.innerHTML = "Por favor, seleccione el check de cubierta para elegir los metros cuadrados";
    } else if (m2tejado == "" && cubierta.checked) {
        result.innerHTML = "Por favor, seleccione una cantidad de metros cuadrados de tejado";
    } else if (isNaN(m2tejado)) {
        result.innerHTML = "Por favor, introduce un número en la cantidad de metros cuadrados de tejado";
    } else {
        result.innerHTML = "El presupuesto total es " + total + "€";
    }
}

function calcularFont() {
    let total = 0;
    let result = document.getElementById("resultadoFont");

    const cambioTuberias = document.getElementById("cambioTuberias");
    const instGrifos = document.getElementById("instGrifos");
    const instTermo = document.getElementById("instTermo");
    const materialTuberia = document.querySelector('input[name="materialTuberia"]:checked').value;
    const mlTuberia = document.getElementById("mlTuberia").value;
    let valorMaterial;

    if (materialTuberia == "cobre") {
        valorMaterial = 25;
    } else if (materialTuberia == "pvc") {
        valorMaterial = 15;
    } else if (materialTuberia == "ppr") {
        valorMaterial = 20;
    }

    if (cambioTuberias.checked) {
        total += 500;
    }
    if (instGrifos.checked) {
        total += 150;
    }
    if (instTermo.checked) {
        total += 300;
    }
    if (mlTuberia !== "" && !isNaN(mlTuberia) && cambioTuberias.checked) {
        total += mlTuberia * valorMaterial;
    }

    if (mlTuberia != "" && !cambioTuberias.checked) {
        result.innerHTML = "Por favor, seleccione el check de cambio de tuberías para indicar los metros lineales.";
    } else if (mlTuberia == "" && cambioTuberias.checked) {
        result.innerHTML = "Por favor, introduzca la cantidad de metros lineales de tubería.";
    } else if (isNaN(mlTuberia)) {
        result.innerHTML = "Por favor, introduzca un número válido en los metros lineales de tubería.";
    } else {
        result.innerHTML = "El presupuesto total es " + total + "€";
    }
}
