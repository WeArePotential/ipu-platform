/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

(function ($, Drupal, window, document, undefined) {

    'use strict';

    Drupal.behaviors.membersMapIPU = {

        attach: function (context, settings) {

            $(function () {

                var test_plots = {};

                var getElemID = function (elem) {
                    // Show element ID
                    return $(elem.node).attr("data-id");
                };

                var getLink = function (elem) {
                    // Show element ID
                    return $(elem.node).attr("data-id");
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
                                fill: "#00A6B6",
                                stroke: "#5d5d5d",
                                "stroke-width": 0.2,
                                "stroke-linejoin": "round"
                            },
                            attrsHover: {
                                fill: "#E98300",
                                animDuration: 300
                            },
                            tooltip: {
                                content: getElemID
                            },
                            href: {
                                content: getLink
                            }
                        },

                        defaultPlot: {
                            size: 9
                        }
                    },
                    areas: {
                        "AF": {
                            "value": "56",
                            "tooltip": {"content": "Afghanistany",},
                            attrs: {"stroke-width": 0.1,},
                            "href": 'http://www.scip.org.uk',

                        },
                        "Albania": {
                            "value": "103",
                            href: "/country/AL",
                            "tooltip": {"content": "Albania",},
                            attrs: {"stroke-width": 0.1,}
                        },
                        "Algeria": {"value": "157", "tooltip": {"content": "Algeria",}, attrs: {"stroke-width": 0.1,}},
                        "Andorra": {"value": "103", "tooltip": {"content": "Andorra",}, attrs: {"stroke-width": 0.1,}},
                        "Angola": {"value": "179", "tooltip": {"content": "Angola",}, attrs: {"stroke-width": 0.1,}},
                        "Antigua-and-Barbuda": {"value": "228", "tooltip": {}, attrs: {"stroke-width": 0.1,}},
                        "Argentina": {
                            "value": "228",
                            "tooltip": {"content": "Argentina",},
                            attrs: {"stroke-width": 0.1,}
                        },
                        "Armenia": {"value": "103", "tooltip": {"content": "Armenia",}, attrs: {"stroke-width": 0.1,}},
                        "Asia and the Pacific": {
                            "value": "0",
                            "tooltip": {"content": "Asia and the Pacific",},
                            attrs: {"stroke-width": 0.1,}
                        },
                        "Australia": {
                            "value": "56",
                            "tooltip": {"content": "Australia",},
                            attrs: {"stroke-width": 0.1,}
                        },
                        "Austria": {"value": "103", "tooltip": {"content": "Austria",}, attrs: {"stroke-width": 0.1,}},
                        "Azerbaijan": {"value": "103", "tooltip": {"content": "Azerbaijan",}},
                        "Bahamas": {"value": "228", "tooltip": {"content": "Bahamas",}},
                        "Bahrain": {"value": "157", "tooltip": {"content": "Bahrain",}},
                        "Bangladesh": {"value": "56", "tooltip": {"content": "Bangladesh",}},
                        "Barbados": {
                            "value": "228",
                            attrs: {fill: "#6F7376", "stroke-width": 0.1,},
                            "tooltip": {"content": "Barbados: Non Member",}
                        },
                        "Belarus": {"value": "103", "tooltip": {"content": "Belarus",}},
                        "Belgium": {"value": "103", "tooltip": {"content": "Belgium",}},
                        "Belize": {"value": "228", "tooltip": {"content": "Belize",}},
                        "Benin": {"value": "179", "tooltip": {"content": "Benin",}},
                        "Bhutan": {"value": "56", "tooltip": {"content": "Bhutan",}},
                        "Bolivia-Plurinational-State-of": {
                            "value": "228",
                            "tooltip": {"content": "Bolivia (Plurinational State of): Member",}
                        },
                        "Bosnia-and-Herzegovina": {
                            "value": "103",
                            "tooltip": {"content": "Bosnia and Herzegovina: Member",}
                        },
                        "Botswana": {"value": "179", "tooltip": {"content": "Botswana",}},
                        "Brazil": {"value": "228", "tooltip": {"content": "Brazil",}},
                        "Brunei Darussalam: Non Member": {
                            "value": "56",
                            "tooltip": {"content": "Brunei Darussalam: Non Member",}
                        },
                        "Bulgaria": {"value": "103", "tooltip": {"content": "Bulgaria",}},
                        "Burkina-Faso": {"value": "179", "tooltip": {"content": "Burkina Faso: Member",}},
                        "Burundi": {"value": "179", "tooltip": {"content": "Burundi",}},
                        "Cambodia": {"value": "56", "tooltip": {"content": "Cambodia",}},
                        "Cameroon": {"value": "179", "tooltip": {"content": "Cameroon",}},
                        "Canada": {"value": "228", "tooltip": {"content": "Canada",}},
                        "Cape-Verde": {"value": "179", "tooltip": {"content": "Cape Verde",}},
                        "Central African Republic": {
                            "value": "179",
                            "tooltip": {"content": "Central African Republic",}
                        },
                        "Chad": {"value": "179", "tooltip": {"content": "Chad",}},
                        "Chile": {"value": "228", "tooltip": {"content": "Chile",}},
                        "China": {"value": "56", "tooltip": {"content": "China",}},
                        "Colombia": {"value": "228", "tooltip": {"content": "Colombia: Member",}},
                        "Comoros": {"value": "179", "tooltip": {"content": "Comoros",}},
                        "Congo": {"value": "179", "tooltip": {"content": "Congo",}},
                        "Costa-Rica": {"value": "228", "tooltip": {"content": "Costa Rica: Member",}},
                        "Côte d'Ivoire": {"value": "179", "tooltip": {"content": "Côte d'Ivoire: Member",}},
                        "Croatia": {"value": "103", "tooltip": {"content": "Croatia",}},
                        "Cuba": {"value": "228", "tooltip": {"content": "Cuba",}},
                        "Cyprus": {"value": "103", "tooltip": {"content": "Cyprus",}},
                        "Czech-Republic": {"value": "103", "tooltip": {"content": "Czech Republic: Member",}},
                        "Democratic People s Republic of Korea": {
                            "value": "56",
                            "tooltip": {"content": "Democratic People s Republic of Korea",}
                        },
                        "Democratic-Republic-of-the-Congo": {
                            "value": "179",
                            "tooltip": {"content": "Democratic Republic of the Congo: Member",}
                        },
                        "Denmark": {"value": "103", "tooltip": {"content": "Denmark",}},
                        "Djibouti": {"value": "179", "tooltip": {"content": "Djibouti",}},
                        "Dominica": {"value": "228", "tooltip": {"content": "Dominica",}},
                        "Dominican-Republic": {"value": "228", "tooltip": {"content": "Dominican Republic: Member",}},
                        "Ecuador": {"value": "228", "tooltip": {"content": "Ecuador",}},
                        "El-Salvador": {"value": "228", "tooltip": {"content": "El Salvador: Member",}},
                        "Equatorial-Guinea": {"value": "179", "tooltip": {"content": "Equatorial Guinea: Member",}},
                        "Eritrea": {"value": "179", "tooltip": {"content": "Eritrea",}},
                        "Estonia": {"value": "103", "tooltip": {"content": "Estonia",}},
                        "Ethiopia: Member": {"value": "179", "tooltip": {"content": "Ethiopia: Member",}},
                        "Europe and the CIS": {"value": "0", "tooltip": {"content": "Europe and the CIS",}},
                        "Fiji": {"value": "56", "tooltip": {"content": "Fiji: Member",}},
                        "Finland": {"value": "103", "tooltip": {"content": "Finland",}},
                        "Former-Yugoslav-Republic-of-Macedonia": {
                            "value": "103",
                            "tooltip": {"content": "Former Yugoslav Republic of Macedonia",}
                        },
                        "France": {"value": "103", "tooltip": {"content": "France",}},
                        "Gabon": {"value": "179", "tooltip": {"content": "Gabon",}},
                        "Gambia": {"value": "179", "tooltip": {"content": "Gambia",}},
                        "Georgia": {"value": "103", "tooltip": {"content": "Georgia",}},
                        "Germany": {"value": "103", "tooltip": {"content": "Germany",}},
                        "Ghana": {"value": "179", "tooltip": {"content": "Ghana",}},
                        "Greece": {"value": "103", "tooltip": {"content": "Greece",}},
                        "Grenada: Non Member": {
                            "value": "228",
                            attrs: {fill: "#6F7376", "stroke-width": 0.1,},
                            "tooltip": {"content": "Grenada: Non Member",}
                        },
                        "Guatemala": {"value": "228", "tooltip": {"content": "Guatemala",}},
                        "Guinea": {"value": "179", "tooltip": {"content": "Guinea",}},
                        "Guinea-Bissau": {"value": "179", "tooltip": {"content": "Guinea-Bissau",}},
                        "Guyana": {"value": "228", "tooltip": {"content": "Guyana",}},
                        "Haiti": {"value": "228", "tooltip": {"content": "Haiti",}},
                        "Honduras": {"value": "228", "tooltip": {"content": "Honduras",}},
                        "Hungary": {"value": "103", "tooltip": {"content": "Hungary",}},
                        "Iceland": {"value": "103", "tooltip": {"content": "Iceland",}},
                        "Indonesia": {"value": "56", "tooltip": {"content": "Indonesia",}},
                        "Iran (Islamic Republic of)": {
                            "value": "56",
                            "tooltip": {"content": "Iran Islamic Republic of",}
                        },
                        "Iraq": {"value": "157", "tooltip": {"content": "Iraq",}},
                        "Ireland": {"value": "103", "tooltip": {"content": "Ireland",}},
                        "Israel": {"value": "157", "tooltip": {"content": "Israel",}},
                        "Italy": {"value": "103", "tooltip": {"content": "Italy",}},
                        "Jamaica": {"value": "228", "tooltip": {"content": "Jamaica",}},
                        "Japan": {"value": "56", "tooltip": {"content": "Japan",}},
                        "Jordan": {"value": "157", "tooltip": {"content": "Jordan",}},
                        "Kazakhstan": {"value": "56", "tooltip": {"content": "Kazakhstan",}},
                        "Kenya": {"value": "179", "tooltip": {"content": "Kenya",}},
                        "Kiribati: Non Member": {
                            "value": "56",
                            "tooltip": {"content": "Kiribati: Non Member",},
                            attrs: {fill: "#6F7376", "stroke-width": 0.1,}
                        },
                        "Kuwait": {"value": "157", "tooltip": {"content": "Kuwait",}},
                        "Kyrgyzstan": {"value": "56", "tooltip": {"content": "Kyrgyzstan",}},
                        "Lao People's Democratic Republic": {
                            "value": "56",
                            "tooltip": {"content": "Lao People's Democratic Republic",}
                        },
                        "Latvia": {"value": "103", "tooltip": {"content": "Latvia",}},
                        "Lebanon": {"value": "157", "tooltip": {"content": "Lebanon",}},
                        "Lesotho": {"value": "179", "tooltip": {"content": "Lesotho",}},
                        "Liberia": {"value": "179", "tooltip": {"content": "Liberia",}},
                        "Libya": {"value": "157", "tooltip": {"content": "Libya",}},
                        "Liechtenstein": {"value": "103", "tooltip": {"content": "Liechtenstein",}},
                        "Lithuania": {"value": "103", "tooltip": {"content": "Lithuania",}},
                        "Luxembourg": {"value": "103", "tooltip": {"content": "Luxembourg",}},
                        "Madagascar": {"value": "179", "tooltip": {"content": "Madagascar",}},
                        "Malawi": {"value": "179", "tooltip": {"content": "Malawi",}},
                        "Malaysia": {"value": "56", "tooltip": {"content": "Malaysia",}},
                        "Maldives": {"value": "56", "tooltip": {"content": "Maldives",}},
                        "Mali": {"value": "179", "tooltip": {"content": "Mali: Member",}},
                        "Malta": {"value": "103", "tooltip": {"content": "Malta",}},
                        "Marshall-Islands": {"value": "56", "tooltip": {"content": "Marshall Islands",}},
                        "Mauritania": {"value": "179", "tooltip": {"content": "Mauritania",}},
                        "Mauritius": {"value": "179", "tooltip": {"content": "Mauritius",}},
                        "Mexico": {"value": "228", "tooltip": {"content": "Mexico",}},
                        "Micronesia (Federated States of): Member": {
                            "value": "56",
                            "tooltip": {"content": "Micronesia (Federated States of): Member",}
                        },
                        "Moldova": {"value": "103", "tooltip": {"content": "Republic of Moldova: Member",}},
                        "Monaco": {"value": "103", "tooltip": {"content": "Monaco",}},
                        "Mongolia": {"value": "56", "tooltip": {"content": "Mongolia",}},
                        "Montenegro": {"value": "103", "tooltip": {"content": "Montenegro",}},
                        "Morocco": {"value": "157", "tooltip": {"content": "Morocco",}},
                        "Mozambique": {"value": "179", "tooltip": {"content": "Mozambique",}},
                        "Myanmar": {"value": "56", "tooltip": {"content": "Myanmar",}},
                        "Namibia": {"value": "179", "tooltip": {"content": "Namibia",}},
                        "Nauru: Non Member": {
                            "value": "56",
                            "tooltip": {"content": "Nauru: Non Member",},
                            attrs: {fill: "#6F7376", "stroke-width": 0.1,}
                        },
                        "Nepal": {"value": "56", "tooltip": {"content": "Nepal",}},
                        "Netherlands": {"value": "103", "tooltip": {"content": "Netherlands",}},
                        "New-Zealand": {"value": "56", "tooltip": {"content": "New Zealand",}},
                        "Nicaragua": {"value": "228", "tooltip": {"content": "Nicaragua",}},
                        "Niger": {"value": "179", "tooltip": {"content": "Niger",}},
                        "Nigeria": {"value": "179", "tooltip": {"content": "Nigeria",}},
                        "Niue": {"value": "56", "tooltip": {"content": "Niue",}},
                        "North-Africa-and-Middle-East": {
                            "value": "0",
                            "tooltip": {"content": "North Africa and Middle East",}
                        },
                        "Norway": {"value": "103", "tooltip": {"content": "Norway",}},
                        "Oman": {"value": "157", "tooltip": {"content": "Oman",}},
                        "Palau": {"value": "56", "tooltip": {"content": "Palau",}},
                        "Panama": {"value": "228", "tooltip": {"content": "Panama",}},
                        "Papua-New-Guinea": {"value": "56", "tooltip": {"content": "Papua New Guinea",}},
                        "Paraguay": {"value": "228", "tooltip": {"content": "Paraguay",}},
                        "Peru": {"value": "228", "tooltip": {"content": "Peru",}},
                        "Philippines": {"value": "56", "tooltip": {"content": "Philippines",}},
                        "Poland": {"value": "103", "tooltip": {"content": "Poland",}},
                        "Portugal": {"value": "103", "tooltip": {"content": "Portugal",}},
                        "Qatar": {"value": "157", "tooltip": {"content": "Qatar",}},
                        "Republic-of-Korea": {"value": "56", "tooltip": {"content": "Republic of Korea: Member",}},
                        "Romania": {"value": "103", "tooltip": {"content": "Romania",}},
                        "Russian-Federation": {"value": "103", "tooltip": {"content": "Russian Federation",}},
                        "Rwanda": {"value": "179", "tooltip": {"content": "Rwanda",}},
                        "Saint-Kitts-and-Nevis": {"value": "228", "tooltip": {"content": "Saint Kitts and Nevis",}},
                        "Saint-Lucia": {"value": "228", "tooltip": {"content": "Saint Lucia",}},
                        "Saint-Vincent-and-the-Grenadines": {
                            "value": "228",
                            "tooltip": {"content": "Saint Vincent and the Grenadines",}
                        },
                        "Samoa": {"value": "56", "tooltip": {"content": "Samoa",}},
                        "San-Marino": {"value": "103", "tooltip": {"content": "San Marino",}},
                        "Sao-Tome-and-Principe": {"value": "179", "tooltip": {"content": "Sao Tome and Principe",}},
                        "Saudi-Arabia": {"value": "157", "tooltip": {"content": "Saudi Arabia",}},
                        "Senegal": {"value": "179", "tooltip": {"content": "Senegal: Member",}},
                        "Serbia": {"value": "103", "tooltip": {"content": "Serbia",}},
                        "Seychelles": {"value": "179", "tooltip": {"content": "Seychelles",}},
                        "Sierra-Leone": {"value": "179", "tooltip": {"content": "Sierra Leone",}},
                        "Singapore": {"value": "56", "tooltip": {"content": "Singapore",}},
                        "Slovakia": {"value": "103", "tooltip": {"content": "Slovakia",}},
                        "Slovenia": {"value": "103", "tooltip": {"content": "Slovenia",}},
                        "Solomon-Islands: Non Member": {
                            "value": "56",
                            attrs: {fill: "#6F7376", "stroke-width": 0.1,},
                            "tooltip": {"content": "Solomon Islands: Non Member",}
                        },
                        "Somalia: Member": {"value": "179", "tooltip": {"content": "Somalia: Member",}},
                        "South-Africa": {"value": "179", "tooltip": {"content": "South Africa",}},

                        "Spain": {"value": "103", "tooltip": {"content": "Spain",}},
                        "Sri-Lanka": {"value": "56", "tooltip": {"content": "Sri Lanka",}},
                        "Sub-Saharan-Africa": {"value": "0", "tooltip": {"content": "Sub Saharan Africa",}},
                        "Suriname": {"value": "228", "tooltip": {"content": "Suriname",}},
                        "Eswatini": {"value": "179", "tooltip": {"content": "Eswatini",}},
                        "Sweden": {"value": "103", "tooltip": {"content": "Sweden",}},
                        "Switzerland": {"value": "103", "tooltip": {"content": "Switzerland",}},
                        "Syrian-Arab-Republic": {"value": "157", "tooltip": {"content": "Syrian Arab Republic",}},
                        "Tajikistan": {"value": "56", "tooltip": {"content": "Tajikistan",}},
                        "Thailand": {"value": "56", "tooltip": {"content": "Thailand",}},
                        "The-Americas-and-the-Caribbean": {
                            "value": "0",
                            "tooltip": {"content": "The Americas and the Caribbean",}
                        },
                        "Timor-Leste": {"value": "56", "tooltip": {"content": "Timor-Leste",}},
                        "Togo": {"value": "179", "tooltip": {"content": "Togo",}},
                        "Tonga": {"value": "56", "tooltip": {"content": "Tonga",}},
                        "Trinidad-and-Tobago": {"value": "228", "tooltip": {"content": "Trinidad and Tobago",}},
                        "Tunisia": {"value": "157", "tooltip": {"content": "Tunisia",}},
                        "Turkey": {"value": "103", "tooltip": {"content": "Turkey",}},
                        "Turkmenistan": {"value": "56", "tooltip": {"content": "Turkmenistan",}},
                        "Tuvalu": {"value": "56", "tooltip": {"content": "Tuvalu",}},
                        "Uganda": {"value": "179", "tooltip": {"content": "Uganda",}},
                        "Ukraine": {"value": "103", "tooltip": {"content": "Ukraine",}},
                        "United-Arab-Emirates": {"value": "157", "tooltip": {"content": "United Arab Emirates",}},
                        "United-Kingdom-of-Great-Britain-and-Northern-Ireland": {
                            "value": "103",
                            "tooltip": {"content": "United Kingdom: Member",}
                        },
                        "United Republic of Tanzania": {
                            "value": "179",
                            "tooltip": {"content": "United Republic of Tanzania",}
                        },
                        "United States of America": {
                            "value": "228",
                            "tooltip": {"content": "United States of America",}
                        },
                        "Uruguay": {"value": "228", "tooltip": {"content": "Uruguay",}},
                        "Uzbekistan": {"value": "56", "tooltip": {"content": "Uzbekistan",}},
                        "Vanuatu: Member": {
                            "value": "56",
                            "tooltip": {"content": "Vanuatu: Member",},
                            attrs: {"stroke-width": 0.1,}
                        },
                        "Venezuela": {"value": "228", "tooltip": {"content": "Venezuela",}},
                        "Viet-Nam": {"value": "56", "tooltip": {"content": "Viet Nam: Member",}},
                        "Yemen": {"value": "157", "tooltip": {"content": "Yemen",}},
                        "Zambia": {"value": "179", "tooltip": {"content": "Zambia",}},
                        "Zimbabwe": {"value": "179", "tooltip": {"content": "Zimbabwe",}},

                        "Cyprus": {"value": "103", "tooltip": {"content": "Cyprus",}, attrs: {"stroke-width": 0}},
                        "Western Sahara": {"value": "179", "tooltip": {"content": "WESTERN SAHARA",}},
                        "Pakistan": {
                            "value": "56", "tooltip": {"content": "Pakistan",}, attrs: {
                                stroke: "none", "stroke-width": 0.8, "stroke-dasharray": ".",
                            }
                        },

                        "Jammu-and-Kashmir: Non Member": {
                            "value": "56",
                            "tooltip": {"content": "Jammu and Kashmir",},
                            attrs: {stroke: "none",}
                        },
                        "Gaza": {
                            "value": "157", "tooltip": {"content": "Palestine: Member",}, attrs: {
                                stroke: "none", "stroke-width": 0,
                            },
                        },
                        "South Sudan": {
                            "value": "179",
                            "tooltip": {"content": "South Sudan: Member <br> <i>Note: Final boundary between the Republic of Sudan and the Republic of South Sudan has not yet been determined.</i>",},
                            attrs: {stroke: "grey",}
                        },
                        "Egypt: Member": {
                            "value": "157",
                            "tooltip": {"content": "Egypt: Member"},
                            attrs: {stroke: "none",}
                        },
                        "Sudan: member": {
                            "value": "179",
                            "tooltip": {"content": "Sudan: Member",},
                            attrs: {stroke: "none",}
                        },
                        "West Bank: Member": {
                            "value": "157", "tooltip": {"content": "Palestine: Member",}, attrs: {
                                "stroke-dasharray": ".",  // stroke : "red",  //"stroke-width" : 0,
                            }
                        },
                        "Republic of Korea": {
                            "value": "56",
                            "tooltip": {"content": "Republic of Korea",},
                            attrs: {stroke: "none",}
                        },
                        "Democratic-People's-Republic-of-Korea": {
                            "value": "56", "tooltip": {"content": "Democratic People's Republic of Korea: Member",}
                            , attrs: {stroke: "none",}
                        },

                        "India": {
                            "value": "56", "tooltip": {"content": "India",}
                            ,
                        },

                        "Greenland": {
                            "value": "103",

                            "tooltip": {
                                "content": "Greenland (Denmark)",
                            }
                        },
                        "French-Southern-and-Antarctic-Lands": {
                            "value": "103",

                            "tooltip": {
                                "content": "French Southern and Antarctic Lands",
                            }, attrsHover: {
                                transform: "s3"
                                , animDuration: 300
                            }
                        },
                        /*"Taiwan-Province-of-China"   : {
                         "value": "56",
                         "href": "http://iknowpolitics.org/"+lang+"/region/China",
                         "tooltip": {
                         "content": "Taiwan, Province of China",
                         }
                         },*/
                        "Puerto-Rico": {
                            "value": "228",

                        },
                        "Falkland-Islands": {
                            "value": "103",
                            "tooltip": {
                                "content": "Falkland Islands (Malvinas): Non Member<br>Note:A dispute exists between the Governments of Argentina and the United Kingdom of Great Britain and Northern Ireland concerning sovereignty over the Falkland Islands (Malvinas).",
                            },
                            attrsHover: {
                                transform: "s3"
                                , animDuration: 300

                            }
                        },


                        "French-Guiana": {
                            "value": "103",
                            "tooltip": {
                                "content": "French Guiana (France)",
                            }
                        },


                        "New Caledonia": {
                            "value": "179",
                            "tooltip": {
                                "content": "New Caledonia (France)",
                            }, attrsHover: {
                                transform: "s3"
                                , animDuration: 300
                            }
                        },
                        //NC

                        "Egypt2": {
                            "value": "2",

                            attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                "stroke-dasharray": ".",
                            }
                        },

                        "Aksai Chin": {
                            "value": "56",
                            tooltip: {
                                content: "<span style=\"font-weight:bold;\">Paris (75)</span><br />Population : 2268265"
                            }, attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }
                        },
                        "KE-SD": {
                            "value": "179",
                            attrsHover: {
                                fill: "#f00",
                                animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                "stroke-dasharray": ".",   stroke : "red",
                            }
                        },
                        "DRPK-ROK": {
                            "value": "2",
                            attrsHover: {
                                fill: "#f00"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                "stroke-dasharray": ".",   stroke : "red",

                            }
                        },
                        "Somalia": {
                            "value": "2",
                            attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                "stroke-dasharray": ".",
                                stroke: "saddlebrown",
                            }
                        },

                        "JandK": {
                            "value": "2",
                            attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                stroke: "grey",
                                "stroke-dasharray": ".",

                            }
                        },

                        "JandK2": {
                            "value": "2", "tooltip": {
                                "content": "",
                            },

                            attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",

                                "stroke-dasharray": ".",
                            }
                        },


                        "JandK4": {
                            "value": "2",
                            "tooltip": {
                                "content": "",
                            },

                            attrsHover: {
                                fill: "none"
                                , animDuration: 0,
                            }, attrs: {
                                fill: "none",
                                "stroke-dasharray": ".",
                            }
                        },
                        "JandK5": {
                            "value": "2", "tooltip": {
                                "content": "",
                            },

                            attrsHover: {
                                fill: "none"
                                , animDuration: 1,
                            }, attrs: {
                                fill: "none",
                                "stroke-width": 0.3,
                                "stroke-dasharray": ".",
                            }
                        },
                        "South Sudan2": {
                            "value": "2",
                            attrsHover: {
                                fill: "none"
                                , "stroke-width": 0.3, animDuration: "1",
                            }, attrs: {
                                fill: "none",
                                stroke: "grey",
                                "stroke-width": 0.6,
                                "stroke-dasharray": ".",
                            }
                        },
                        "Sudan border": {
                            "value": "2", attrsHover: {fill: "none", animDuration: "",}, attrs: {
                                fill: "none",

                                //"stroke-dasharray" :".",
                            }
                        },
                        "India-border: Member": {
                            "value": "2",
                            attrsHover: {fill: "none", animDuration: "0",}, attrs: {
                                fill: "none",//"stroke-dasharray" :".",
                            }
                        },

                        "JandK-border": {
                            "value": "2", "tooltip": {
                                "content": "",
                            }, attrsHover: {fill: "none", animDuration: "0",}, attrs: {
                                fill: "none", "stroke-dasharray": ".",
                            }
                        },


                        //non members COUNTRIES
                        "Aksai Chin": {
                            attrs: {
                                fill: " #85edfc",
                                "stroke-width": 0.1,


                            }, "tooltip": {
                                "content": "",
                            }
                            , attrsHover: {}
                        },
                        "Western Sahara": {
                            attrs: {
                                fill: "#85edfc",
                                "stroke-width": 0.1,


                            }
                            , "tooltip": {
                                "content": "<i>Western Sahara</i>",
                            }
                            , attrsHover: {}
                        },


                        "Antigua-and-Barbuda: Non Member":
                            {
                                "value": "56",
                                "tooltip": {
                                    "content": "Antigua and Barbuda: Non Member",
                                },
                                attrs: {
                                    stroke: "none",
                                    fill: "#6F7376",
                                }
                            },


                        "Jammu-and-Kashmir: Non Member":
                            {
                                "value": "56",
                                "tooltip": {
                                    "content": "<i>Jammu and Kashmir</i><br>Note: Dotted line represents approximately the Line of Control in Jammu and Kashmir agreed upon by India and Pakistan. The final status of Jammu and Kashmir has not yet been agreed upon by the parties",
                                },
                                attrs: {
                                    stroke: "grey",
                                    fill: " #85edfc",
                                    "stroke-dasharray": ".",
                                    "stroke-width": 0.3,


                                }
                            },


                        "Falkland Islands (Malvinas):<br>Note:A dispute exists between the Governments of Argentina and the United Kingdom of Great Britain and Northern Ireland concerning sovereignty over the Falkland Islands (Malvinas).": {
                            attrs: {
                                fill: "white",
                                "stroke-width": 0.3,


                            }
                            , attrsHover: {}
                        },


                        "Brunei Darussalam: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,


                            }
                            , attrsHover: {}
                        },

                        "Saint Vincent and the Grenadines: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,


                            }
                            , attrsHover: {}
                        },


                        "Saint Kitts and Nevis: Non Member": {
                            attrs: {
                                fill: "#6F7376",


                            }
                            , attrsHover: {}
                        },


                        "Eritrea: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }
                            , attrsHover: {}
                        },
                        "Liberia: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,


                            }
                            , attrsHover: {}
                        },
                        "Cook Islands: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }
                            , attrsHover: {}
                        },
                        "Niue": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }
                            , attrsHover: {}
                        },
                        "Puerto Rico(USA)": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }, "tooltip": {
                                "content": "Puerto Rico (USA)",
                            }

                            , attrsHover: {}
                        },
                        "Jamaica: Non Member": {
                            attrs: {
                                fill: "#6F7376",


                            }
                            , attrsHover: {}
                        },
                        "Belize: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,


                            }
                            , attrsHover: {}
                        },


                        "Bahamas: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }
                            , attrsHover: {}
                        },
                        "United States of America: Non Member": {
                            attrs: {
                                fill: "#6F7376",
                                "stroke-width": 0.1,

                            }
                            , attrsHover: {}
                        },
                        "Central African Republic: Member": {
                            attrs: {

                                "stroke-width": 0.1,


                            }
                            , attrsHover: {}
                        }
                    },

                    plots: test_plots
                });

            });


        }
    };


})(jQuery, Drupal, this, this.document);