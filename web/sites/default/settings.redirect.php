<?php

// 301 Redirect for references to documents to archive.ipu.org
$pattern = "/^(\/fr)?(\/(cnl|conf|splz|news|parline|wmn|dem|hr|press|strct|Un|finance|cntr|eb|idd)-(e|f)\/(.+))$/i";
$matches = [];
$pdf_pattern = "/^(\/fr)?(\/pdf\/(.+)\.pdf)$/i";
$pdf_matches = [];

$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
$redirect = FALSE;
$archive_website = 'http://archive.ipu.org';

if ($uri) {
  $permanent_redirect = FALSE;
  if (preg_match($pattern, $uri, $matches)) {
    $permanent_redirect = TRUE;
    $redirect = "{$archive_website}{$matches[2]}"; // Fix to remove the /fr prefix as archive do not have it (Use regex capture group 1)
  }
  else {
    if (preg_match($pdf_pattern, $uri, $pdf_matches)) {
      $permanent_redirect = TRUE;
      $redirect = "{$archive_website}{$pdf_matches[2]}";
    }
    else {
      if (in_array($uri, ["/parline/", "/parline"])) {
        $permanent_redirect = TRUE;
        $redirect = "{$archive_website}/parline/";
      }
      else {
        if (in_array($uri, ["/parline-e/", "/parline-e"])) {
          $permanent_redirect = TRUE;
          $redirect = "{$archive_website}/parline-e/parlinesearch.asp";
        }
        else {
          if (in_array($uri, ["/idd/", "/idd"])) {
            $permanent_redirect = TRUE;
            $redirect = "{$archive_website}/idd/";
          }
          else {
            if (in_array($uri, ["/gpr/", "/gpr"])) {
              $permanent_redirect = TRUE;
              $redirect = "{$archive_website}/gpr/";
            }
            else {
              if ($uri == '/home') {
                $redirect = "/";
              }
              else {
                if ($uri == '/ipu_admin' || $uri == '/ipu_admin/') {
                  $redirect = "{$archive_website}/ipu_admin";
                }

                else {
                  // Generic redirect for asp and html.
                  $pattern = "/^\/(.+)[.](htm|asp)$/";
                  $styles_pattern = "/^\/Styles\/(.+)$/";
                  if (preg_match($pattern, $uri)) {
                    $permanent_redirect = TRUE;
                    $redirect = "{$archive_website}{$uri}";
                  }
                  else {
                    if ($uri == '/our-work/strong-parliaments/international-day-democracy') {
                      $permanent_redirect = TRUE;
                      $redirect = '/our-work/strong-parliaments/international-day-democracy-0';
                    }
                    else {
                      if ($uri == '/fr/our-work/strong-parliaments/journee-internationale-de-la-democratie') {
                        $permanent_redirect = TRUE;
                        $redirect = '/fr/our-work/strong-parliaments/journee-internationale-de-la-democratie-0';
                      }
                      else {
                        if (preg_match($styles_pattern, $uri)) {
                          $redirect = "{$archive_website}{$uri}";
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  //--------------------------------------------------------------------------
  // Upcoming events temporary redirects
  //--------------------------------------------------------------------------
  // 137th Assembly and related meetings
  if ($uri == '/event/137th-ipu-assembly-and-related-meetings') {
    $redirect = 'http://archive.ipu.org/conf-e/137agnd.htm';
  }
  else {
    if ($uri == '/fr/event/137eme-assemblee-de-luip-et-reunions-connexes' || $uri == '/fr/event/137eme-assemblee-de-luip-et-reunions-connexes-0') {
      $redirect = 'http://archive.ipu.org/conf-f/137agnd.htm';
    }
  }

  //Parliamentary meeting on the occasion of the United Nations Climate Change Conference
  if ($uri == '/event/parliamentary-meeting-occasion-un-climate-change-conference') {
    $redirect = 'http://archive.ipu.org/splz-e/cop23.htm';
  }
  else {
    if ($uri == '/fr/event/reunion-parlementaire-loccasion-de-la-conference-des-nations-unies-sur-les-changements-climatiques') {
      $redirect = 'http://archive.ipu.org/splz-f/cop23.htm';
    }
  }

  //Promoting better regional cooperation towards smart and humane migration across the Mediterranean
  if ($uri == '/event/promoting-better-regional-cooperation-towards-smart-and-humane-migration-across-mediterranean') {
    $redirect = 'http://archive.ipu.org/splz-e/valletta17.htm';
  }
  else {
    if ($uri == '/fr/event/promouvoir-une-meilleure-cooperation-regionale-pour-des-migrations-sensees-et-humaines-en-mediterranee') {
      $redirect = 'http://archive.ipu.org/splz-f/valletta17.htm';
    }
  }

  //Fourth IPU Global Conference of Young Parliamentarians
  if ($uri == '/event/fourth-ipu-global-conference-young-parliamentarians') {
    $redirect = 'http://archive.ipu.org/splz-e/youngmp17.htm';
  }
  else {
    if ($uri == '/fr/event/quatrieme-conference-mondiale-des-jeunes-parlementaires-de-luip') {
      $redirect = 'http://archive.ipu.org/splz-f/youngMP17.htm';
    }
  }

  //Translating international human rights commitments into national realities
  if ($uri == '/event/translating-international-human-rights-commitments-national-realities-role-parliaments-and-their-contribution-universal-periodic-review-united') {
    $redirect = 'http://archive.ipu.org/splz-e/salvador17.htm';
  }
  else {
    if ($uri == '/fr/event/seminaire-regional-sur-le-theme-traduire-les-engagements-internationaux-en-matiere-de-droits-de-lhomme-en-realites-nationales-la-contribution') {
      $redirect = 'http://archive.ipu.org/splz-f/salvador17.htm';
    }
  }

  //Buenos Aires session of the Parliamentary Conference on the WTO
  if ($uri == '/event/annual-session-parliamentary-conference-wto') {
    $redirect = 'http://archive.ipu.org/splz-e/trade17.htm';
  }
  else {
    if ($uri == '/fr/event/session-annuelle-de-la-conference-parlementaire-sur-lomc') {
      $redirect = 'http://archive.ipu.org/splz-f/trade17.htm';
    }
  }

  //12th summit of women speakers of parliament
  if ($uri == '/event/12th-summit-women-speakers-parliament-please-check-back-here-future-information') {
    $redirect = 'http://archive.ipu.org/splz-e/Cochabamba17.htm';
  }
  else {
    if ($uri == '/fr/event/12eme-sommet-des-presidentes-de-parlement-sil-vous-plait-verifier-ici-pour-information-future') {
      $redirect = 'http://archive.ipu.org/splz-f/Cochabamba17.htm';
    }
  }

  //Some images
  if (strtolower($uri) == '/images/signature/img_logo_ipu_f.png') {
    $permanent_redirect = TRUE;
    $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_f.png';
  }
  if (strtolower($uri) == '/images/signature/get-engaged-now.png') {
    $permanent_redirect = TRUE;
    $redirect = 'http://archive.ipu.org/images/signature/get-engaged-now.png';
  }
  if (strtolower($uri) == '/images/signature/get-engaged-now-fr.png') {
    $permanent_redirect = TRUE;
    $redirect = 'http://archive.ipu.org/images/signature/get-engaged-now-fr.png';
  }
  if (strtolower($uri) == '/images/signature/img_logo_ipu_e.png') {
    $permanent_redirect = TRUE;
    $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_e.png';
  }
  if (strtolower($uri) == '/images/signature/img_logo_ipu_fs.gif') {
    $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_fs.gif';
  }

  $list_english_no_redirect = [
    '/event/regional-seminar-contribution-parliament-promotion-and-protection-rights-child-occasion-cemac-parliamentary-session-please-check-back-here',
    '/event/conference-gender-equality-committees-in-framework-joint-ipu-un-women-and-committee-equal-opportunity-women-and-men-parliament-turkey-project',
    '/event/regional-seminar-parliaments-and-implementation-un-security-council-resolution-1540-please-check-back-here-future-information',
    '/event/conference-counter-terrorism-please-check-back-here-future-information',
    '/event/regional-seminar-migration-please-check-back-here-future-information',
    '/event/regional-seminar-latin-american-and-caribbean-parliaments-financial-inclusion-women-please-check-back-here-future-information',
    // '/event/138th-assembly-and-related-meetings-please-check-back-here-future-information',
    '/event/regional-seminar-sdgs-parliaments-eastern-and-central-europe-and-central-asia-please-check-back-here-future-information',
    '/event/third-south-asian-speakers-summit-achieving-sdgs-please-check-back-here-future-information',
    '/event/ipu-unisdr-sub-regional-seminar-northeast-asia-disaster-risk-reduction-and-2030-agenda-sustainable-development-please-check-back-here-future',
    // '/event/annual-parliamentary-hearing-united-nations-please-check-back-here-future-information'
  ];

  if (in_array($uri, $list_english_no_redirect)) {
    $redirect = '/events';
  }
  else {
    $list_french_no_redirect = [
      '/fr/event/seminaire-regional-sur-la-contribution-des-parlements-en-matiere-de-promotion-et-de-protection-des-droits-de-lenfant-loccasion-de-la-session',
      '/fr/event/conference-pour-les-commissions-sur-legalite-des-sexes-dans-le-cadre-du-projet-conjoint-de-luip-donu-femmes-et-de-la-commission-sur-legalite-des',
      '/fr/event/seminaire-regional-sur-les-parlements-et-la-mise-en-oeuvre-de-la-resolution-1540-du-conseil-de-securite-de-lonu-sil-vous-plait-verifier-ici-pour',
      '/fr/event/conference-sur-la-lutte-contre-le-terrorisme-sil-vous-plait-verifier-ici-pour-information-future',
      '/fr/event/seminaire-regional-sur-les-migrations-sil-vous-plait-verifier-ici-pour-information-future',
      '/fr/event/seminaire-regional-pour-les-parlements-damerique-latine-et-des-caraibes-sur-linclusion-financiere-des-femmes-sil-vous-plait-verifier-ici-pour',
      // '/fr/event/138eme-assemblee-et-reunions-connexes-sil-vous-plait-verifier-ici-pour-information-future',
      '/fr/event/seminaire-regional-pour-les-parlements-deurope-centrale-et-orientale-et-dasie-centrale-sur-les-odd-sil-vous-plait-verifier-ici-pour-information',
      '/fr/event/troisieme-sommet-des-presidents-de-parlement-dasie-du-sud-sur-la-mise-en-oeuvre-des-odd-sil-vous-plait-verifier-ici-pour-information-future',
      '/fr/event/seminaire-sous-regional-uip-unisdr-pour-les-pays-dasie-du-nord-est-sur-la-reduction-des-risques-de-catastrophe-et-le-programme-de-developpement',
      // '/fr/event/audition-parlementaire-annuelle-aux-nations-unies-sil-vous-plait-verifier-ici-pour-information-future'
    ];

    if (in_array($uri, $list_french_no_redirect)) {
      $redirect = '/fr/node/9838';
    }
  }
  if (strtolower($uri) == '/fr/news/press-releases') {
    $redirect = '/fr/actualites/communiques-de-presse';
  }
  else {
    if (strtolower($uri) == '/fr/news/news-in-brief') {
      $redirect = '/fr/actualites/actualites-en-bref';
    }
    else {
      if (strtolower($uri) == '/fr/news/features') {
        $redirect = '/fr/actualites/articles';
      }
      else {
        if (strtolower($uri) == '/fr/news/features/profiles') {
          $redirect = '/fr/actualites/articles/profils';
        }
        else {
          if (strtolower($uri) == '/fr/news/features/human-rights-cases') {
            $redirect = '/fr/actualites/articles/cas-datteinte-aux-droits-de-lhomme-des-parlementaires';
          }
        }
      }
    }
  }

  //--------------------------------------------------------------------------
}
if ($redirect && php_sapi_name() != "cli") {
  if ($permanent_redirect) {
    header("HTTP/1.1 301 Moved Permanently");
  }
  header('Location: ' . $redirect);
  exit();
}
