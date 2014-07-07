 // Config tour steps
            var tourSteps = [{
                "msg": "Bienvenido al tour de mi.chilevision.cl, presione iniciar para comenzar.", // tour bubble / dialog text
                "actionName": false, // name of Mixpanel event used for funnel analysis - spaces are fine, use friendly names. You'll need to setup MP yourself however and include the libs.
                "selector": "body", // selector for highlighted feature. Comma seperated list = (dialog target, additional items to pop above mask). Don't forget your '.' or '#'
                "position": "center", // dialog location in relation to target (selector). top, bottom, left, right, (or 'center' which centers to screen)
                "btnMsg": "Iniciar &raquo", // if you'd like a button on the dialog simply add a message here
                "nextSelector": "#tour_dialog_btn", // does the user need to do something specific to advance? For example, clicking the tour bubble ok button. Omit for any action click to advance.
                "waitForTrigger": false // should we pause the tour here? while the user does something? Pass a seletor as the trigger to resume the tour from this point
            }, {
                "msg": "El boton Home en envia a todos los datos de tu cuenta. Podras visualizar tu informacion basica, tus modulos activos y notificaciones mas recientes para que estes al tanto de lo que pasa cada segundo en el sistema.",
                    "selector": ".step1",
                    "position": "left"
            }, {
                "msg": "Este boton te redirige a tus preferencias del sistema, podras configurar tus notificaciones a traves de pop-up, agregarle sonido de alerta, enviar las notificaciones a tu correo electronico y cambiar tu foto de perfil.",
                    "selector": ".step2",
                    "position": "left",
            }, {
                "msg": "El boton de candado cierra de manera segura tu sesion.",
                    "selector": ".step3",
                    "position": "bottom"
            }, {
                "msg": "Esta barra muestra tus modulos asociados al sitio en todo momento y donde sea que estes navegando por el sitio, ademas de desplegar las opciones mas rapidas y usadas para dichos modulos.",
                    "selector": ".step4",
                    "position": "right"
            }, {
                "msg": "Eso es todo! Comienza a explorar el sitio! Si tienes alguna duda puedes descargar el manuel de uso en la opcion 'Preferencias'.",
                    "selector": "body",
                    "position": "center",
                    "btnMsg": "Finalizar &raquo"
            }];

            // fire off the tour when ready
            $.tour(tourSteps);