var transforms = document.getElementsByClassName("transform");
console.log(transforms);
var index = 1;
setInterval(function() {
    var imgs = [];
    for (var i = 0; i < transforms.length; i++) {
        imgs = transforms[i].getElementsByTagName("img");
        for (var j = 0; j < imgs.length; j++)
            if (imgs[j].getAttribute("data-order") == index)
                imgs[j].style.zIndex = 10;
       		else
                imgs[j].style.zIndex = 5;
    }
    index++;
    if (index > imgs.length)
        index = 1;
}, 1500);