// If you using SweetAlert2 (Swal) and JQuery you can replace all alert and confirm that way.
// https://limonte.github.io/sweetalert2/
// TIP: if you aren't using JQuery, use native JavaScript to create extend method. As bellow:
/*
function extend(a, b){
    for(var key in b)
        if(b.hasOwnProperty(key))
            a[key] = b[key];
    return a;
}
*/

// keep default js alert to use in specific cases
window.legacyAlert = window.alert;

// types alert and confirm: "success", "error", "warning", "info", "question". Default: "warning"
// overwrite default js alert
window.alert = function(msg, title, type, params) {
    var title = (title == null) ? 'Aviso' : title;
    var type = (type == null) ? 'warning' : type;
    swal($.extend({
            title: title,
            text: msg,
            type: type,
            timer: 5000
        }, params || {})
    );
};

// keep default js alert to use in specific cases
window.legacyConfirm = window.confirm;

window.confirm = function(msg, title, type, func_if_yes, func_if_cancel, params) {
    var title = (title == null) ? 'Confirmação' : title;
    var type = (type == null) ? 'warning' : type;
    swal($.extend({
                    title: title,
                    text: msg,
                    type: type,
                    timer: 10000,
                    showCancelButton: true,
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Ok",
                    allowEscapeKey: false,
                    allowOutsideClick: false
                }, params || {})
    ).then(function(isConfirm) {
        if (isConfirm && func_if_yes instanceof Function){
            func_if_yes();
        }
    }, function(dismiss) {
        // dismiss can be 'cancel', 'overlay', 'close', 'timer'
        if (dismiss === 'cancel' && func_if_cancel instanceof Function) {
            func_if_cancel()
        }
    })
};

// Now you can call alert("Test") or confirm("Test") and you will see Swal Alerts.