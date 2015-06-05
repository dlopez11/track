{{ stylesheet_link('library/notification-styles/css/ns-default.min.css') }}
{{ stylesheet_link('library/notification-styles/css/ns-style-bar.min.css') }}
{{ javascript_include('library/notification-styles/js/notificationFx.min.js') }}

<script type="text/javascript">
    function slideOnTop(message, time, icon, type) {
        var notification = new NotificationFx({
            wrapper : document.body,
            message : '<span class="' + icon + '"></span><p>' + message + '</p>',
            layout : 'bar',
            effect : 'slidetop',
            type : type, // notice, warning or error
            ttl : time,
            onClose : function() {

            }
        });

        // show the notification
        notification.show();
    };
</script>