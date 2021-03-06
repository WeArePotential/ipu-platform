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
            var current_url = $(location). attr("pathname");
            current_url.indexOf(1);
            current_url.toLowerCase();
            var current_lang = current_url.split("/")[1] == 'fr' ? 'fr' : '';
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
                var has_parent_parliament = false;
                if (data[index].field_iso_code_for_parliament != '') {
                    iso_code_for_parliament = data[index].field_iso_code_for_parliament;
                    has_parent_parliament = true;
                }
                var colour = ((member) ? '#00A6B6' : '#6F7376');
                colour = ((no_parliament_page) ? '#85edfc' : colour);
                var attr = {
                    name: data[index].name,
                    ipu_member: member,
                    principles_signatory: signatory,
                    has_parent_parliament: has_parent_parliament,
                    no_parliament_page: no_parliament_page,
                    description: data[index].description__value,
                    attrs: {"stroke-width": 0.4, "fill": colour},
                };
                // TODO: Get parliaments page url from config
                if (no_parliament_page) {
                    // attr.description = attr.description + '<p>No data available.</p>';
                } else {
                    if (current_lang == 'fr') {
                        attr.href = '/fr/parlement/' + iso_code_for_parliament.toLowerCase();
                    } else {
                        attr.href = '/parliament/' + iso_code_for_parliament.toLowerCase();
                    }
                }
                settings.areas[data[index].field_iso_code] = attr;
            }

            var getElemID = function (elem) {
                var tooltip = '';
                var iso_code = $(elem.node).attr("data-id");
                if (iso_code in settings.areas) {
                    var data = settings.areas[iso_code];
                    var memberTxt = '<div class="text">' + ((data.ipu_member) ? (current_lang == 'fr' ? 'Membre de l\'UIP' : 'IPU member') : (current_lang == 'fr' ? 'Non-membre de l\'UIP' : 'Not an IPU member')) + '</div>';
                    var signatoryTxt = ((data.principles_signatory) ? '<div class="text">' + (current_lang == 'fr' ? 'Adhérent aux Principes communs' : 'Signatory to common principles') + '</div>' : '')
                    if (data.has_parent_parliament || data.no_parliament_page) {
                        // Don't show notes if there's a parent parliament
                        memberTxt = '';
                        signatoryTxt = '';
                    }
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
                        maxLevel: 20,
                        init: {
                            level: 1,
                            latitude: 45,
                            longitude: -10,
                        }
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