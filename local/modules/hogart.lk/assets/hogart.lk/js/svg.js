/**
 * Created by gillbeits on 01/11/2016.
 */
"use strict";

(function ($) {
    "use strict";

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function getLinePoints(startElem, endElem, svgContainer) {
        if (startElem.offset().top > endElem.offset().top) {
            var temp = startElem;
            startElem = endElem;
            endElem = temp;
        }

        var svgTop = svgContainer.offset().top;
        var svgLeft = svgContainer.offset().left;

        // get (top, left) coordinates for the two elements
        var startCoord = startElem.offset();
        var endCoord = endElem.offset();

        var startX = startCoord.left + startElem.outerWidth() - svgLeft;
        var startY = startCoord.top + 0.5 * startElem.outerHeight() - svgTop;

        // calculate path's end (x,y) coords
        var endX = endCoord.left + endElem.outerWidth() - svgLeft;
        var endY = endCoord.top + 0.5 * endElem.outerHeight() - svgTop;

        return { startX: Math.ceil(startX), startY: Math.ceil(startY), endX: Math.ceil(endX), endY: Math.ceil(endY) };

    }

    function relation (startSelector, endSelector, options) {
        var self = this;

        options = $.extend({
            rnd: 20,
            strokeWidth: 4,
            strokeColor: getRandomColor()
        }, options);

        if (!Snap) {
            throw new Error("No SNAP Library installed!");
        }

        var paper = Snap("#relations")
            .attr({ width: "100%", height: "100%", viewBox: "0 0 " + $(this).outerWidth() + " " + $(this).outerHeight() });

        $(endSelector).each(function (i, eElement) {
            var data;
            data = $(startSelector).data("relationHistoryStack") || [];
            if (data.indexOf(eElement) >= 0) return;

            var points = getLinePoints($(startSelector), $(eElement), $(self));

            var deltaX = options.rnd * (0.5 + ~~(Math.random() * 100) / 100);
            var deltaY = (points.endY - points.startY) * 0.15;
            var arc1 = 0; var arc2 = 1;
            if (points.startY < points.endY) {
                arc1 = 1;
                arc2 = 0;
            }
            var rndY = ~~(Math.random() * 10 * options.strokeWidth);
            points.startY -= rndY;
            points.endY -= rndY;

            // draw tha pipe-like path
            // 1. move a bit down, 2. arch,  3. move a bit to the right, 4.arch, 5. move down to the end
            paper.path("M"  + points.startX + " " + points.startY
                + "H" + (points.startX + deltaX)
                + "A" + deltaX + " " +  deltaX + " 0 0 " + arc1 + " " + (points.startX + 2*deltaX*Math.sign(deltaY)) + " " + (points.startY + deltaX)
                + "V" + (points.endY - deltaX * Math.sign(deltaY))
                + "A" + deltaX + " " +  deltaX + " 0 0 " + arc1 + " " + (points.endX + deltaX*Math.sign(deltaY)) + " " + points.endY
                + "H" + points.endX
                )
                .attr({
                    fill: 'transparent',
                    stroke: options.strokeColor,
                    strokeWidth: options.strokeWidth
                })
            ;

            data.push(eElement);
            $(startSelector).data("relationHistoryStack", data);
            data = $(endSelector).data("relationHistoryStack") || [];
            data.push($(startSelector));
            $(endSelector).data("relationHistoryStack", data);
        });
    }

    $.fn.relationHistory = relation;
})(jQuery);