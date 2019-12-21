<base href="{{$proxyPath}}/"/>
<script>
    // let oldXHROpen = window.XMLHttpRequest.prototype.open;
    // window.XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
    //     this.addEventListener('load', function() {
    //         console.log('load: ' + this.responseText);
    //     });
    //     return oldXHROpen.apply(this, arguments);
    // };

    document
        .getElementsByTagName('head')[0]
        .addEventListener('DOMNodeInserted', function(e) {
            if (e.path) {
                if (Array.isArray(e.path)) {

                }
            }
        });
</script>
