window.onload = function () {
    document.getElementById("imgBano").addEventListener("click", cambiarPresupuestoBano);
    document.getElementById("imgTejado").addEventListener("click", cambiarPresupuestoTejado);
    document.getElementById("imgCocina").addEventListener("click", cambiarPresupuestoCocina);
    document.getElementById("imgFontaneria").addEventListener("click", cambiarPresupuestoFontaneria);
    document.getElementById("bano").classList.add("oculto");
    document.getElementById("cocina").classList.add("oculto");
    document.getElementById("tejado").classList.add("oculto");
    document.getElementById("fontaneria").classList.add("oculto");

    document.getElementById("bCalcPresupuesto").addEventListener("click", calcularBano);
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
        result.innerHTML = "Por favor, introduce un numero en la cantidad de metros cuadrados de azulejo";
    } else {
        result.innerHTML = "El presupuesto total es " + total + "€";
    }
}
