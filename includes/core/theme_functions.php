<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/13/2010 20:00
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * nv_error_info()
 *
 * @return
 */
function nv_error_info()
{
    global $lang_global, $global_config, $error_info;

    if (!defined('NV_IS_ADMIN')) {
        return;
    }
    if (empty($error_info)) {
        return;
    }

    $errortype = [
        E_ERROR => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_WARNING => [
            $lang_global['error_warning'],
            'warning.png'
        ],
        E_PARSE => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_NOTICE => [
            $lang_global['error_notice'],
            'comment.png'
        ],
        E_CORE_ERROR => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_CORE_WARNING => [
            $lang_global['error_warning'],
            'warning.png'
        ],
        E_COMPILE_ERROR => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_COMPILE_WARNING => [
            $lang_global['error_warning'],
            'warning.png'
        ],
        E_USER_ERROR => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_USER_WARNING => [
            $lang_global['error_warning'],
            'warning.png'
        ],
        E_USER_NOTICE => [
            $lang_global['error_notice'],
            'comment.png'
        ],
        E_STRICT => [
            $lang_global['error_notice'],
            'comment.png'
        ],
        E_RECOVERABLE_ERROR => [
            $lang_global['error_error'],
            'bad.png'
        ],
        E_DEPRECATED => [
            $lang_global['error_notice'],
            'comment.png'
        ],
        E_USER_DEPRECATED => [
            $lang_global['error_warning'],
            'warning.png'
        ]
    ];

    if (defined('NV_ADMIN') and file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/error_info.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
        $image_path = NV_BASE_SITEURL . 'themes/' . $global_config['admin_theme'] . '/images/icons/';
    } elseif (defined('NV_ADMIN')) {
        $tpl_path = NV_ROOTDIR . '/themes/admin_default/system';
        $image_path = NV_BASE_SITEURL . 'themes/admin_default/images/';
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/error_info.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system';
        $image_path = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/icons/';
    } else {
        $tpl_path = NV_ROOTDIR . '/themes/default/system';
        $image_path = NV_BASE_SITEURL . 'themes/default/images/icons/';
    }

    $xtpl = new XTemplate('error_info.tpl', $tpl_path);
    $xtpl->assign('TPL_E_CAPTION', $lang_global['error_info_caption']);

    $a = 0;
    foreach ($error_info as $key => $value) {
        $xtpl->assign('TPL_E_CLASS', ($a % 2) ? ' class="second"' : '');
        $xtpl->assign('TPL_E_ALT', $errortype[$value['errno']][0]);
        $xtpl->assign('TPL_E_SRC', $image_path . $errortype[$value['errno']][1]);
        $xtpl->assign('TPL_E_ERRNO', $errortype[$value['errno']][0]);
        $xtpl->assign('TPL_E_MESS', $value['info']);
        $xtpl->set_autoreset();
        $xtpl->parse('error_info.error_item');
        ++$a;
    }

    $xtpl->parse('error_info');
    return $xtpl->text('error_info');
}

/**
 * nv_info_die()
 *
 * @param string $page_title
 * @param mixed $info_title
 * @param mixed $info_content
 * @param string $admin_link
 * @param string $admin_title
 * @param string $site_link
 * @param string $site_title
 * @return
 */
function nv_info_die($page_title = '', $info_title, $info_content, $error_code = 200, $admin_link = NV_BASE_ADMINURL, $admin_title = '', $site_link = NV_BASE_SITEURL, $site_title = '')
{
    global $lang_global, $global_config;

    http_response_code($error_code);

    if (empty($page_title)) {
        $page_title = $global_config['site_description'];
    }

    // Get theme
    $template = '';
    if (defined('NV_ADMIN') and isset($global_config['admin_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/info_die.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
        $template = $global_config['admin_theme'];
    } elseif (defined('NV_ADMIN') and file_exists(NV_ROOTDIR . '/themes/admin_default/system/info_die.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/admin_default/system';
        $template = 'admin_default';
    } elseif (isset($global_config['module_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/system/info_die.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/system';
        $template = $global_config['module_theme'];
    } elseif (isset($global_config['site_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/info_die.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system';
        $template = $global_config['site_theme'];
    } else {
        $tpl_path = NV_ROOTDIR . '/themes/default/system';
        $template = 'default';
    }

    $xtpl = new XTemplate('info_die.tpl', $tpl_path);
    $xtpl->assign('SITE_CHARSET', $global_config['site_charset']);
    $xtpl->assign('PAGE_TITLE', $page_title);
    $xtpl->assign('HOME_LINK', $global_config['site_url']);
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('SITE_NAME', empty($global_config['site_name']) ? '' : $global_config['site_name']);

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }
    $xtpl->assign('SITE_FAVICON', $site_favicon);

    $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->assign('INFO_TITLE', $info_title);
    $xtpl->assign('INFO_CONTENT', $info_content);

    if (defined('NV_IS_ADMIN') and !empty($admin_link)) {
        $xtpl->assign('ADMIN_LINK', $admin_link);
        $xtpl->assign('GO_ADMINPAGE', empty($admin_title) ? $lang_global['admin_page'] : $admin_title);
        $xtpl->parse('main.adminlink');
    }
    if (!empty($site_link)) {
        $xtpl->assign('SITE_LINK', $site_link);
        $xtpl->assign('GO_SITEPAGE', empty($site_title) ? $lang_global['go_homepage'] : $site_title);
        $xtpl->parse('main.sitelink');
    }

    $xtpl->parse('main');

    include NV_ROOTDIR . '/includes/header.php';
    $xtpl->out('main');
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 *
 * @param string $html
 * @param string $type
 */
function nv_htmlOutput($html, $type = 'html')
{
    global $global_config, $headers, $nv_BotManager;

    // Xuất cấu hình robot vào header
    $sys_info = [];
    $nv_BotManager->outputToHeaders($headers, $sys_info);

    $html_headers = $global_config['others_headers'];
    if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
        $html_headers['X-Frame-Options'] = 'SAMEORIGIN';
    }
    if ($type == 'json') {
        $html_headers['Content-Type'] = 'application/json';
    } else {
        $html_headers['Content-Type'] = 'text/html; charset=' . $global_config['site_charset'];
    }
    $html_headers['Last-Modified'] = gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT';
    $html_headers['Cache-Control'] = 'max-age=0, no-cache, no-store, must-revalidate'; // HTTP 1.1.
    $html_headers['Pragma'] = 'no-cache'; // HTTP 1.0.
    $html_headers['Expires'] = '-1'; // Proxies.
    $html_headers['X-Content-Type-Options'] = 'nosniff';
    $html_headers['X-XSS-Protection'] = '1; mode=block';

    if (str_contains(NV_USER_AGENT, 'MSIE')) {
        $html_headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
    }

    if (!empty($headers)) {
        // $headers sẽ ghi đè $html_headers
        $html_headers = array_merge($html_headers, $headers);
    }

    if (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] != 'on') {
        unset($html_headers['Strict-Transport-Security']);
    }

    foreach ($html_headers as $key => $value) {
        $_key = strtolower($key);
        if (!is_array($value)) {
            $value = [
                $value
            ];
        }

        foreach ($value as $val) {
            $replace = ($key != 'link') ? true : false;
            Header($key . ': ' . $val, $replace);
        }
    }

    ob_start('ob_gzhandler');
    echo $html;
    exit(0);
}

/**
 *
 * @param array $array_data
 */
function nv_jsonOutput($array_data)
{
    nv_htmlOutput(json_encode($array_data), 'json');
}

/**
 * nv_xmlOutput()
 *
 * @param string $content
 * @param mixed $lastModified
 * @return void
 */
function nv_xmlOutput($content, $lastModified)
{
    if (class_exists('tidy', false)) {
        $tidy_options = [
            'input-xml' => true,
            'output-xml' => true,
            'indent' => true,
            'indent-cdata' => true,
            'wrap' => false
        ];
        $tidy = new tidy();
        $tidy->parseString($content, $tidy_options, 'utf8');
        $tidy->cleanRepair();
        $content = (string) $tidy;
    } else {
        $content = trim($content);
    }

    @Header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
    @Header('Expires: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
    @Header('Content-Type: text/xml; charset=utf-8');

    if (!empty($_SERVER['SERVER_SOFTWARE']) and strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')) {
        @Header('Cache-Control: no-cache, pre-check=0, post-check=0');
    } else {
        @Header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
    }

    if (defined('NV_ADMIN') or NV_ANTI_IFRAME != 0) {
        Header('X-Frame-Options: SAMEORIGIN');
    }

    Header('X-Content-Type-Options: nosniff');
    Header('X-XSS-Protection: 1; mode=block');

    @Header('Pragma: no-cache');

    $encoding = 'none';

    if (nv_function_exists('gzencode') and isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        $encoding = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ? 'gzip' : (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') ? 'deflate' : 'none');

        if ($encoding != 'none') {
            if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') and preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
                $version = floatval($matches[1]);

                if ($version < 6 or ($version == 6 and !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))) {
                    $encoding = 'none';
                }
            }
        }
    }

    if ($encoding != 'none') {
        $content = gzencode($content, 6, $encoding == 'gzip' ? FORCE_GZIP : FORCE_DEFLATE);
        header('Content-Encoding: ' . $encoding);
        header('Content-Length: ' . strlen($content));
        header('Vary: Accept-Encoding');
    }

    print_r($content);
    exit(0);
}

/**
 *
 * @param array $channel
 * @param array $items
 * @param string $timemode
 * @param boolean $noindex
 */
function nv_rss_generate($channel, $items, $timemode = 'GMT', $noindex = true)
{
    global $global_config, $client_info;

    $xtpl = new XTemplate('rss.tpl', NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl');
    // Chi co tac dung voi IE6 va Chrome
    $xtpl->assign('CSSPATH', NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/rss.xsl');
    $xtpl->assign('CHARSET', $global_config['site_charset']);
    $xtpl->assign('SITELANG', $global_config['site_lang']);

    $channel['generator'] = 'NukeViet v4.0';
    $channel['title'] = nv_htmlspecialchars($channel['title']);
    $channel['atomlink'] = str_replace('&', '&amp;', $client_info['selfurl']);
    $channel['lang'] = $global_config['site_lang'];
    $channel['copyright'] = $global_config['site_name'];

    $channel['docs'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=rss', true);
    if (!str_starts_with($channel['docs'], NV_MY_DOMAIN)) {
        $channel['docs'] = NV_MY_DOMAIN . $channel['docs'];
    }

    if (preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN . NV_BASE_SITEURL) . '(.+)$/', $channel['link'], $matches)) {
        $channel['link'] = NV_BASE_SITEURL . $matches[1];
    }
    $channel['link'] = nv_url_rewrite($channel['link'], true);
    if (!str_starts_with($channel['link'], NV_MY_DOMAIN)) {
        $channel['link'] = NV_MY_DOMAIN . $channel['link'];
    }

    if (preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN . NV_BASE_SITEURL) . '(.+)$/', $channel['atomlink'], $matches)) {
        $channel['atomlink'] = NV_BASE_SITEURL . $matches[1];
    }
    $channel['atomlink'] = nv_url_rewrite($channel['atomlink'], true);
    if (!str_starts_with($channel['atomlink'], NV_MY_DOMAIN)) {
        $channel['atomlink'] = NV_MY_DOMAIN . $channel['atomlink'];
    }

    $channel['pubDate'] = 0;
    $channel['modified'] = 0;

    if (!empty($items)) {
        foreach ($items as $item) {
            if (!empty($item['title']) and !empty($item['link'])) {
                $item['title'] = nv_htmlspecialchars($item['title']);

                if (isset($item['pubdate']) and !empty($item['pubdate'])) {
                    $item['pubdate'] = intval($item['pubdate']);
                    $channel['pubDate'] = max($channel['pubDate'], $item['pubdate']);
                    if ($timemode == 'ISO8601') {
                        $item['pubdate'] = date('c', $item['pubdate']);
                    } else {
                        $item['pubdate'] = gmdate('D, j M Y H:m:s', $item['pubdate']) . ' GMT';
                    }
                }
                if (!empty($item['modifydate'])) {
                    $channel['modified'] = max($channel['modified'], $item['modifydate']);
                }

                if (preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN . NV_BASE_SITEURL) . '(.+)$/', $item['link'], $matches)) {
                    $item['link'] = NV_BASE_SITEURL . $matches[1];
                }
                $item['link'] = nv_url_rewrite($item['link'], true);
                if (!str_starts_with($item['link'], NV_MY_DOMAIN)) {
                    $item['link'] = NV_MY_DOMAIN . $item['link'];
                }

                $xtpl->assign('ITEM', $item);

                if (isset($item['guid']) and !empty($item['guid'])) {
                    $xtpl->parse('main.item.guid');
                }
                if (isset($item['pubdate']) and !empty($item['pubdate'])) {
                    $xtpl->parse('main.item.pubdate');
                }
                if (isset($item['author']) and !empty($item['author'])) {
                    $xtpl->parse('main.item.author');
                }
                if (isset($item['content']) and !empty($item['content'])) {
                    if (!empty($item['content']['image'])) {
                        $xtpl->parse('main.item.content.image');
                    }
                    if (!empty($item['content']['opkicker'])) {
                        $xtpl->parse('main.item.content.opkicker');
                    }
                    if (!empty($item['content']['pubdate'])) {
                        if ($timemode == 'ISO8601') {
                            $published = date('c', $item['content']['pubdate']);
                        } else {
                            $published = gmdate('D, j M Y H:m:s', $item['content']['pubdate']) . ' GMT';
                        }
                        $xtpl->assign('PUBLISHED', $published);
                        $xtpl->assign('PUBLISHED_DISPLAY', nv_date('H:i: d/m/Y', $item['content']['pubdate']));
                        $xtpl->parse('main.item.content.pubdate');
                    }
                    if (!empty($item['content']['modifydate'])) {
                        if ($timemode == 'ISO8601') {
                            $modified = date('c', $item['content']['modifydate']);
                        } else {
                            $modified = gmdate('D, j M Y H:m:s', $item['content']['modifydate']) . ' GMT';
                        }
                        $xtpl->assign('MODIFIED', $modified);
                        $xtpl->assign('MODIFIED_DISPLAY', nv_date('H:i: d/m/Y', $item['content']['modifydate']));
                        $xtpl->parse('main.item.content.modifydate');
                    }

                    $xtpl->parse('main.item.content');
                }

                $xtpl->parse('main.item');
            }
        }
    }

    $lastModified = NV_CURRENTTIME;

    if (!empty($channel['pubDate'])) {
        $lastModified = $channel['pubDate'];
        if ($timemode == 'ISO8601') {
            $channel['pubDate'] = date('c', $channel['pubDate']);
        } else {
            $channel['pubDate'] = gmdate('D, j M Y H:m:s', $channel['pubDate']) . ' GMT';
        }
    }

    if ($channel['modified'] > $lastModified) {
        $lastModified = $channel['modified'];
    }

    $xtpl->assign('CHANNEL', $channel);

    if (!empty($channel['description'])) {
        $xtpl->parse('main.description');
    }

    if (!empty($channel['pubDate'])) {
        $xtpl->parse('main.pubDate');
    }

    $image = file_exists(NV_ROOTDIR . '/' . $global_config['site_logo']) ? NV_ROOTDIR . '/' . $global_config['site_logo'] : NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/images/logo.png';
    $image = nv_ImageInfo($image, 144, true, NV_UPLOADS_REAL_DIR);

    if (!empty($image)) {
        $resSize = nv_imageResize($image['width'], $image['height'], 144, 400);
        $image['width'] = $resSize['width'];
        $image['height'] = $resSize['height'];
        $image['title'] = $channel['title'];
        $image['link'] = $channel['link'];

        $image['src'] = nv_url_rewrite($image['src'], true);
        if (!str_starts_with($image['src'], NV_MY_DOMAIN)) {
            $image['src'] = NV_MY_DOMAIN . $image['src'];
        }

        $xtpl->assign('IMAGE', $image);
        $xtpl->parse('main.image');
    }

    $xtpl->parse('main');
    $content = $xtpl->text('main');

    if ($noindex) {
        global $nv_BotManager;
        $nv_BotManager->setNoIndex()
            ->setFollow()
            ->printToHeaders();
    }

    nv_xmlOutput($content, $lastModified);
}

/**
 *
 * @param array $url
 * @param string $changefreq
 * @param string $priority
 */
function nv_xmlSitemap_generate($url, $changefreq = 'daily', $priority = '0.8')
{
    $lastModified = time() - 86400;
    $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/sitemap.xsl"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
    $xml = new SimpleXMLElement($sitemapHeader);
    if (!empty($url)) {
        foreach ($url as $key => $values) {
            $values['link'] = nv_url_rewrite($values['link'], true);
            if (!str_starts_with($values['link'], NV_MY_DOMAIN)) {
                $values['link'] = NV_MY_DOMAIN . $values['link'];
            }
            $row = $xml->addChild('url');
            $row->addChild('loc', $values['link']);
            $row->addChild('lastmod', date('c', $values['publtime']));
            $row->addChild('changefreq', !empty($values['changefreq']) ? $values['changefreq'] : $changefreq);
            $row->addChild('priority', !empty($values['priority']) ? $values['priority'] : $priority);

            if ($key == 0) {
                $lastModified = $values['publtime'];
            }
        }
    }

    $contents = $xml->asXML();
    $contents = nv_url_rewrite($contents);

    nv_xmlOutput($contents, $lastModified);
}

/**
 *
 * @param array $url
 */
function nv_xmlSitemapCat_generate($url)
{
    global $global_config;

    $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/sitemapindex.xsl"?><sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
    $xml = new SimpleXMLElement($sitemapHeader);
    $lastModified = NV_CURRENTTIME - 86400;

    foreach ($url as $link) {
        if (!str_starts_with($link, NV_MY_DOMAIN)) {
            $link = NV_MY_DOMAIN . $link;
        }
        $row = $xml->addChild('sitemap');
        $row->addChild('loc', $link);
    }

    $contents = $xml->asXML();

    if ($global_config['rewrite_enable']) {
        if ($global_config['check_rewrite_file']) {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'sitemap-\\1.\\2.\\3.xml', $contents);
        } elseif ($global_config['rewrite_optional']) {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'index.php/\\2/sitemap/\\3' . $global_config['rewrite_endurl'], $contents);
        } else {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'index.php/\\1/\\2/sitemap/\\3' . $global_config['rewrite_endurl'], $contents);
        }
    }

    nv_xmlOutput($contents, $lastModified);
}

/**
 * nv_xmlSitemapIndex_generate()
 *
 * @return void
 */
function nv_xmlSitemapIndex_generate()
{
    global $db_config, $db, $global_config;

    $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/sitemapindex.xsl"?><sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
    $xml = new SimpleXMLElement($sitemapHeader);

    $lastModified = NV_CURRENTTIME - 86400;

    if ($global_config['lang_multi']) {
        foreach ($global_config['allow_sitelangs'] as $lang) {
            $sql = 'SELECT m.title, m.module_file FROM ' . $db_config['prefix'] . '_' . $lang . '_modules m LEFT JOIN ' . $db_config['prefix'] . '_' . $lang . "_modfuncs f ON m.title=f.in_module WHERE m.act = 1 AND m.groups_view='6' AND m.sitemap=1 AND f.func_name = 'sitemap' ORDER BY m.weight, f.subweight";
            $result = $db->query($sql);
            while (list($modname, $modfile) = $result->fetch(3)) {
                $sitemaps = nv_scandir(NV_ROOTDIR . '/modules/' . $modfile . '/funcs', '/^sitemap(.*?)\.php$/');
                foreach ($sitemaps as $filename) {
                    if (preg_match('/^sitemap(\.*)([a-zA-Z0-9\-]*)\.php$/', $filename, $m)) {
                        if ($m[0] == 'sitemap.php') {
                            $link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=sitemap';
                        } elseif ($m[1] == '.' and $m[2] != '') {
                            $link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=sitemap/' . $m[2];
                        }
                        $row = $xml->addChild('sitemap');
                        $row->addChild('loc', $link);
                    }
                }
            }
        }
    } else {
        $site_mods = nv_site_mods();

        foreach ($site_mods as $modname => $values) {
            if (isset($values['funcs']) and isset($values['funcs']['sitemap']) and !empty($values['sitemap'])) {
                $sitemaps = nv_scandir(NV_ROOTDIR . '/modules/' . $values['module_file'] . '/funcs', '/^sitemap(.*?)\.php$/');
                foreach ($sitemaps as $filename) {
                    if (preg_match('/^sitemap(\.*)([a-zA-Z0-9\-]*)\.php$/', $filename, $m)) {
                        if ($m[0] == 'sitemap.php') {
                            $link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=sitemap';
                        } elseif ($m[1] == '.' and $m[2] != '') {
                            $link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=sitemap/' . $m[2];
                        }
                        $row = $xml->addChild('sitemap');
                        $row->addChild('loc', $link);
                    }
                }
            }
        }
    }
    $db = null;

    $contents = $xml->asXML();

    if ($global_config['rewrite_enable']) {
        if ($global_config['check_rewrite_file']) {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=SitemapIndex/", 'sitemap-\\1.xml', $contents);
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'sitemap-\\1.\\2.\\3.xml', $contents);
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap/", 'sitemap-\\1.\\2.xml', $contents);
        } elseif ($global_config['rewrite_optional']) {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'index.php/\\2/sitemap/\\3' . $global_config['rewrite_endurl'], $contents);
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap/", 'index.php/\\2/sitemap' . $global_config['rewrite_endurl'], $contents);
        } else {
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap\/([a-zA-Z0-9\-]+)/", 'index.php/\\1/\\2/sitemap/\\3' . $global_config['rewrite_endurl'], $contents);
            $contents = preg_replace("/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=sitemap/", 'index.php/\\1/\\2/sitemap' . $global_config['rewrite_endurl'], $contents);
        }
    }

    nv_xmlOutput($contents, $lastModified);
}

/**
 * nv_css_setproperties()
 *
 * @param mixed $tag
 * @param mixed $property_array
 * @return
 */
function nv_css_setproperties($tag, $property_array)
{
    if (empty($tag)) {
        return '';
    }
    if (!is_array($property_array)) {
        return $property_array;
    }

    $css = '';
    foreach ($property_array as $property => $value) {
        if ($property != 'customcss') {
            if (!empty($property) and !empty($value)) {
                $property = str_replace('_', '-', $property);
                if ($property == 'background-image') {
                    $value = "url('" . $value . "')";
                }
                $css .= $property . ':' . $value . ';';
            }
        } elseif (!empty($value)) {
            $value = substr(trim($value), -1) == ';' ? $value : $value . ';';
            $css .= $value;
        }
    }
    !empty($css) and $css = $tag . '{' . $css . '}';
    return $css;
}

/**
 * nv_theme_alert()
 *
 * @param mixed $message_title
 * @param mixed $message_content
 * @param mixed $type
 * @param mixed $url_back
 * @param mixed $lang_back
 * @param mixed $time_back
 * @return
 */
function nv_theme_alert($message_title, $message_content, $type = 'info', $url_back = '', $lang_back = '', $time_back = 5)
{
    global $global_config, $module_info, $lang_module, $page_title;

    if (defined('NV_ADMIN') and file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/alert.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
    } elseif (defined('NV_ADMIN')) {
        $tpl_path = NV_ROOTDIR . '/themes/admin_default/system';
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/alert.tpl')) {
        $tpl_path = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system';
    } else {
        $tpl_path = NV_ROOTDIR . '/themes/default/system';
    }

    $xtpl = new XTemplate('alert.tpl', $tpl_path);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('LANG_BACK', $lang_back);
    $xtpl->assign('CONTENT', $message_content);

    if ($type == 'success') {
        $xtpl->parse('main.success');
    } elseif ($type == 'warning') {
        $xtpl->parse('main.warning');
    } elseif ($type == 'danger') {
        $xtpl->parse('main.danger');
    } else {
        $xtpl->parse('main.info');
    }

    if (!empty($message_title)) {
        $page_title = $message_title;
        $xtpl->assign('TITLE', $message_title);
        $xtpl->parse('main.title');
    } elseif (!empty($module_info['site_title'])) {
        // For admin if use in admin area
        $page_title = $module_info['site_title'];
    } else {
        $page_title = $module_info['custom_title'];
    }

    if (!empty($url_back)) {
        $xtpl->assign('TIME', $time_back);
        $xtpl->assign('URL', $url_back);
        $xtpl->parse('main.url_back');
        $xtpl->parse('main.loading_icon');

        if (!empty($lang_back)) {
            $xtpl->parse('main.url_back_button');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
