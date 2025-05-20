window.onload = function () {
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
