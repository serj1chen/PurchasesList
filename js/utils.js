/**
 * Created by Yarsoniy on 10.06.2015.
 */
function include(url) {
    var script = document.createElement('script');
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}