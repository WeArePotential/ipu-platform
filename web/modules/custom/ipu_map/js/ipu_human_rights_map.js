(function ($, Drupal, window, document, undefined) {

    'use strict';

    Drupal.behaviors.ipuMap = {

        attach: function (context, settings) {
            var ipumap = settings.ipu_map;
            try {
                var data = JSON.parse(ipumap.data);
            } catch(err) {
                $(".mapcontainer_un").append('<p>Sorry, could not get data for the map: '+err.message+'</p>');
                //console.log(ipumap.data);
                return false;
            }
            var current_url = $(location). attr("pathname");
            current_url.indexOf(1);
            current_url.toLowerCase();
            var current_lang = current_url.split("/")[1] == 'fr' ? 'fr' : '';
            settings.areas = [];

            var range = {0: '#eeeeee', 1: '#ffea8a', 2: '#ffd56c', 3: '#ffbf52', 5: '#ffa83a', 7: '#ff8f24', 10: '#ff7211', 20: '#ff4f01', 40: '#ff0000', 50: '#ff0000'};
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
                var human_rights_cases = 0;
                if (data[index].field_human_rights_cases != '') {
                  human_rights_cases = data[index].field_human_rights_cases;
                }
                //var colour = ((signatory) ? '#E98300' : '#6F7376');
                //colour = ((no_parliament_page) ? '#85edfc' : colour);
                /* RANGE  */
                var slices = [];
                var lastVal = 0;
                var colour = range[0];
                for (var val in range) {
                    if (range.hasOwnProperty(val)) {
                      if (parseInt(human_rights_cases) >= val ) {
                        colour = range[val];
                      }
                  }
                }
                var attr = {
                    name: data[index].name,
                    ipu_member: member,
                    principles_signatory: signatory,
                    has_parent_parliament: has_parent_parliament,
                    no_parliament_page: no_parliament_page,
                    human_rights_cases: human_rights_cases,
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
                    var signatoryTxt = ((data.principles_signatory) ? '<div class="text">' + (current_lang == 'fr' ? 'Adhérent aux Principes communs' : 'Signatory to common principles') + '</div>' : '');
                    var humanrightsTxt = ((data.human_rights_cases) ? '<div class="text" style="display:flex;align-items: center"><div class="stats-circle" style="float: left;">' + data.human_rights_cases + '</div><div><strong>&nbsp;' + (current_lang == 'fr' ? '\n' +
                      'Cas de violations des droits' : 'Human Rights cases') + '</strong></div></div>' : '');
                    if (data.has_parent_parliament || data.no_parliament_page) {
                        // Don't show notes if there's a parent parliament
                        memberTxt = '';
                        signatoryTxt = '';
                        humanrightsTxt = '';
                    }
                    var descTxt = '<div class="text country-description">' + data.description + '</div>';

                    var tooltip = '<div class="country-name">' + data.name + '</div>';
                    tooltip = tooltip + memberTxt + signatoryTxt + humanrightsTxt + descTxt;
                } else {
                    tooltip = 'Country not found for ISO Code ' + iso_code;
                }
                return tooltip;
            };

          for (var val in range) {
            if (range.hasOwnProperty(val)) {
              var txt = '';
              if (lastVal == 0) {
                txt = (current_lang == 'fr' ? 'Aucun cas' : 'None');
              } else if (val > 40)
              {
                txt = '> 40 cas';
              } else {
                  if ((val - lastVal) > 1) {
                    txt = lastVal + '-' + (val-1) + '';
                  }
                  else {
                    txt = (lastVal) + '';
                  }
              }
              if (val > 0) {
                slices.push({
                  max: lastVal,
                  attrs: {
                    fill: range[lastVal],
                  },
                  label: txt,
                });
              }
              lastVal = val;
            }
          }
          //console.log(slices);

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
                            fill: "#00a6b6",
                            animDuration: 200
                        },
                        tooltip: {
                            content: getElemID
                        },

                    },
                },
                areas: settings.areas,
              legend: {
                area: {
                  title: (current_lang == 'fr' ? 'Nombre de cas' : 'Number of cases'),
                  display: true,
                  slices: slices,
                  mode: "horizontal",
                }
              },
            });
        }
    };
    //var mapData = jQuery.parseJSON(ipumap.mapdata);
    //var mapValues = jQuery.parseJSON(ipumap.mapvalues);

})(jQuery, Drupal, this, this.document);
