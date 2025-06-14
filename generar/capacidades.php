
    <div class="container">
        <div class="columns">
            <div class="column">
                <h1 class="is-size-1">Probar plugin versión 3</h1>
                <div class="notification is-info">
                    <ol>
                        <li>Asegúrate de que tu impresora esté instalada y compartida como se indica en: <a
                                href="https://parzibyte.me/blog/2017/12/11/instalar-impresora-termica-generica/">https://parzibyte.me/blog/2017/12/11/instalar-impresora-termica-generica/</a>
                        </li>
                        <li>Selecciona tu impresora de la lista. Asegúrate de que la misma sea únicamente térmica, no de
                            etiquetas, no de tinta, láser o distintas a térmicas</li>
                        <li>Presiona el botón <strong>Imprimir</strong> y se debería imprimir algo parecido a lo que ves
                            en la imagen de ejemplo.</li>
                        <li>Dependiendo del modelo de impresora algunas cosas pueden variar, por ejemplo, puede que las
                            imágenes no aparezcan o que el texto con acento salga raro.
                            Ahí es donde entra la documentación y el trabajo del programador</li>
                        <li>Si todo va bien y aparece el ticket, entonces ya puedes usar el plugin en cualquier lenguaje
                            de programación</li>
                        <li>Recuerda que estoy a tus órdenes en <a
                                href="https://parzibyte.me/#contacto">https://parzibyte.me/#contacto</a> </li>
                        <li>
                            Si te lo preguntas, mi impresora es una de las más sencillas del mercado. Es la Xprinter 58
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="select is-rounded">
                    <select id="listaDeImpresoras"></select>
                </div>
                <button id="btnImprimir" class="button is-success">Imprimir</button>
            </div>
            <div class="column">
                <div class="notification is-warning">
                    <p>
                        Al imprimir, el resultado debería ser parecido al siguiente:
                    </p>
                </div>
                <img src="./img/TicketPusheen.jpg" alt="Resultado de impresión de ticket con plugin versión 3">
            </div>
        </div>
    </div>
    <script>

        const obtenerListaDeImpresoras = async () => {
            return await ConectorPluginV3.obtenerImpresoras();
        }
        const amongUsComoCadena = `000001111000
000010000100
000100011110
000100100001
011100100001
010100100001
010100100001
010100011110
010100000010
011100000010
000100111010
000100101010
000111101110
000000000000
000000000000
000000000000
111010101110
100010101000
111010101110
001010100010
111011101110
000000000000
000000000000
000000000000`;
        const URLPlugin = "http://localhost:8000";
        const $listaDeImpresoras = document.querySelector("#listaDeImpresoras"),
            $btnImprimir = document.querySelector("#btnImprimir");
        const init = async () => {
            const impresoras = await ConectorPluginV3.obtenerImpresoras(URLPlugin);
            for (const impresora of impresoras) {
                $listaDeImpresoras.appendChild(Object.assign(document.createElement("option"), {
                    value: impresora,
                    text: impresora,
                }));
            }
            $btnImprimir.addEventListener("click", () => {
                const nombreImpresora = $listaDeImpresoras.value;
                if (!nombreImpresora) {
                    return alert("Por favor seleccione una impresora. Si no hay ninguna, asegúrese de haberla compartido como se indica en: https://parzibyte.me/blog/2017/12/11/instalar-impresora-termica-generica/")
                }
                demostrarCapacidades(nombreImpresora);
            });
        }
        const demostrarCapacidades = async (nombreImpresora) => {
            const conector = new ConectorPluginV3(URLPlugin);
            const respuesta = await conector
                .Iniciar()
                .DeshabilitarElModoDeCaracteresChinos()
                .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
                .DescargarImagenDeInternetEImprimir("https://parzibyte.github.io/ejemplos-javascript-plugin-v3/generar/parzibyte.png", 200, ConectorPluginV3.ALGORITMO_IMAGEN_RASTERIZACION)
                .Feed(1)
                .EscribirTexto("Parzibyte's blog\n")
                .EscribirTexto("Blog de un programador\n")
                .TextoSegunPaginaDeCodigos(2, "cp850", "Teléfono: 123456798\n")
                .EscribirTexto("Fecha y hora: " + (new Intl.DateTimeFormat("es-MX").format(new Date())))
                .Feed(1)
                .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
                .EscribirTexto("____________________\n")
                .TextoSegunPaginaDeCodigos(2, "cp850", "Venta de plugin para impresoras versión 3\n")
                .EstablecerAlineacion(ConectorPluginV3.ALINEACION_DERECHA)
                .EscribirTexto("$25\n")
                .EscribirTexto("____________________\n")
                .EscribirTexto("TOTAL: $25\n")
                .EscribirTexto("____________________\n")
                .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
                .HabilitarCaracteresPersonalizados()
                .DefinirCaracterPersonalizado("$", amongUsComoCadena)
                .EscribirTexto("En lugar del simbolo de pesos debe aparecer un among us\n")
                .EscribirTexto("TOTAL: $25\n")
                .EstablecerEnfatizado(true)
                .EstablecerTamañoFuente(1, 1)
                .TextoSegunPaginaDeCodigos(2, "cp850", "¡Gracias por su compra!\n")
                .Feed(1)
                .ImprimirCodigoQr("https://parzibyte.me/blog", 160, ConectorPluginV3.RECUPERACION_QR_MEJOR, ConectorPluginV3.ALGORITMO_IMAGEN_RASTERIZACION)
                .Feed(1)
                .ImprimirCodigoDeBarrasCode128("parzibyte.me", 80, 192, ConectorPluginV3.ALGORITMO_IMAGEN_RASTERIZACION)
                .Feed(1)
                .EstablecerTamañoFuente(1, 1)
                .EscribirTexto("parzibyte.me\n")
                .Feed(3)
                .Corte(1)
                .Pulso(48, 60, 120)
                .imprimirEn(nombreImpresora);
            if (respuesta === true) {
                alert("Impreso correctamente");
            } else {
                alert("Error: " + respuesta);
            }
        }
        init();
    </script>