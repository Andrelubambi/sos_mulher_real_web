function showNotification(type, message) {
    const alertDiv = $('<div>', {
        class: `alert alert-${type} position-fixed top-0 end-0 m-3 shadow`,
        text: message,
    }).appendTo('body');

    setTimeout(() => {
        alertDiv.fadeOut(500, () => alertDiv.remove());
    }, 3000);
}
 
 