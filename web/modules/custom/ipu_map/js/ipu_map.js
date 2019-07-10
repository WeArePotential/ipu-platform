(function ($, Drupal, window, document, undefined) {

    'use strict';

    Drupal.behaviors.ipuMap = {

        attach: function (context, settings) {
            var ipumap = settings.ipu_map;
            var data = JSON.parse(ipumap.data);

            settings.areas = [];
            for (var index = 0; index < data.length; ++index) {
                var member = (data[index].field_ipu_member != 'False');
                var colour = ((member) ? '#00A6B6' : '#6F7376');
                settings.areas[data[index].field_iso_code] = {
                    name: data[index].name,
                    ipu_member: member,
                    description: data[index].description__value,
                    //Xtooltip: data[index].name + ' :' + memberTxt,
                    attrs: {"stroke-width": 1, "fill": colour},
                    href: '/parliament/' + data[index].field_iso_code.toLowerCase(),
                };
            }

            var getElemID = function (elem) {
                var tooltip = '';
                var iso_code = $(elem.node).attr("data-id");
                if (iso_code in settings.areas) {
                    var data = settings.areas[iso_code];
                    var memberTxt = ((data.ipu_member) ? 'Member' : 'Non member') + '<br />' + data.description;
                    tooltip = data.name + ': ' + memberTxt;
                } else {
                    tooltip = 'Country not found for ISO Code ' + iso_code;
                }
                return tooltip;
            };


            $(".mapcontainer_un").mapael({
                map: {
                    name: "world_countries_un",
                    zoom: {
                        enabled: true,
                        maxLevel: 15
                    },
                    defaultArea: {
                        attrs: {
                            fill: "#eeeeee",
                            stroke: "#5d5d5d",
                            "stroke-width": 0.2,
                            "stroke-linejoin": "round"
                        },
                        attrsHover: {
                            fill: "#E98300",
                            animDuration: 200
                        },
                        tooltip: {
                            content: getElemID
                        },

                    },
                },
                areas: settings.areas,
            });
        }
    };
    //var mapData = jQuery.parseJSON(ipumap.mapdata);
    //var mapValues = jQuery.parseJSON(ipumap.mapvalues);

})(jQuery, Drupal, this, this.document);