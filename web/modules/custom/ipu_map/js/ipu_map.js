(function ($, Drupal, window, document, undefined) {

    'use strict';

    Drupal.behaviors.ipuMap = {

        attach: function (context, settings) {
            var ipumap = settings.ipu_map;
            try {
                var data = JSON.parse(ipumap.data);
            } catch(err) {
                $(".mapcontainer_un").append('<p>Sorry, could not get data for the map: '+err.message+'</p>');
                console.log(ipumap.data);
                return false;
            }

            settings.areas = [];

            /*
             * If we have an iso_code for parliament, use this for the hyperlink
             * Unless the the no_parliament_page is checked, in which case no link needed
             */
            for (var index = 0; index < data.length; ++index) {
                var member = (data[index].field_ipu_member != 'False');
                var signatory = (data[index].field_principles_signatory != 'False');
                var no_parliament_page = (data[index].field_no_parliament_page == 'True');
                var iso_code_for_parliament = data[index].field_iso_code;
                if (data[index].field_iso_code_for_parliament != '') {
                    iso_code_for_parliament = data[index].field_iso_code_for_parliament;
                }
                var colour = ((member) ? '#00A6B6' : '#6F7376');
                colour = ((no_parliament_page) ? '#85edfc' : colour);
                var attr = {
                    name: data[index].name,
                    ipu_member: member,
                    principles_signatory: signatory,
                    description: data[index].description__value,
                    attrs: {"stroke-width": 0.4, "fill": colour},
                };
                if (no_parliament_page) {
                    attr.description = attr.description + '<p>No data available.</p>';
                } else {
                    attr.href = '/parliament/' + iso_code_for_parliament.toLowerCase();
                }
                settings.areas[data[index].field_iso_code] = attr;
            }

            var getElemID = function (elem) {
                var tooltip = '';
                var iso_code = $(elem.node).attr("data-id");
                if (iso_code in settings.areas) {
                    var data = settings.areas[iso_code];
                    var memberTxt = '<div class="text">' + ((data.ipu_member) ? 'IPU member' : 'Not an IPU member') + '</div>';
                    var signatoryTxt = ((data.principles_signatory) ? '<div class="text">Signatory to common princples</div>' : '')
                    var descTxt = '<div class="text country-description">' + data.description + '</div>';

                    var tooltip = '<div class="country-name">' + data.name + '</div>';
                    tooltip = tooltip + memberTxt + signatoryTxt + descTxt;
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