<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
return array(
        'index'=>array(
//		'aaaaaaaaaaaa'=>'javascript:javascript::alert(11);',
                'menu_index_home'=>'home',
                'menu_index_forum_add'=>'forums&do=add',
                'menu_index_article_add'=>'article&do=add',
                'menu_index_comment'=>'comment',
                'menu_index_article_user_draft'=>'article&do=manage&act=user&type=draft',
                'menu_index_link'=>'link',
                'menu_index_advertise'=>'advertise',
        ),
        'setting'=>array(
                'menu_setting_all'=>'setting',
                'menu_setting_config'=>'setting&do=config',
                'menu_setting_url'=>'setting&do=url',
                'menu_setting_tag'=>'setting&do=tag',
                'menu_setting_cache'=>'setting&do=cache',
                'menu_setting_attachments'=>'setting&do=attachments',
                'menu_setting_watermark'=>'setting&do=watermark',
                'menu_setting_publish'=>'setting&do=publish',
                'menu_setting_time'=>'setting&do=time',
                'menu_setting_other'=>'setting&do=other',
//                'menu_setting_patch'=>'setting&do=patch',
//		'menu_setting_bbs'=>'setting&do=bbs',
        ),
        'article'=>array(
                array(
                        'menu_forums_manage'=>'forums',
                        'menu_forums_add'=>'forums&do=add',
                ),
                array(
                        'menu_article_manage'=>'article&do=manage',
                        'menu_article_add'=>'article&do=add',
                ),
                'menu_article_manage'=>"javascript:forumTree();",
                'menu_article_draft'=>'article&do=manage&type=draft',
                'menu_article_trash'=>'article&do=manage&type=trash',
                array(
                        'menu_article_user_manage'=>'article&do=manage&type=user',
                        'menu_article_user_draft'=>'article&do=manage&type=user&act=draft',
                ),
                'menu_comment'=>'comment&mid=0',
                'menu_contentype'=>'contentype',
                array(
                'menu_defaults'=>'defaults',
                'menu_filter'=>'filter',
                ),
                'menu_tag_manage'=>'tag&do=manage',
                array(
                        'menu_keywords'=>'keywords',
                        'menu_keywords_add'=>'keywords&do=add',
                ),
                array(
                        'menu_search'=>'search',
                        'menu_search_add'=>'search&do=add',
                ),
//		'menu_push_add'=>'push&do=add',
//		'menu_push_forum'=>'push&do=forums',
//		'menu_push_manage'=>'push&do=manage',
        ),
        'user'=>array(
                'menu_user_manage'=>'user&do=manage',
                //	'menu_user_add'=>'user&do=add',
                //	'menu_article_user_manage'=>'article&do=manage&act=user',
                array(
                //	'menu_article_user_draft'=>'article&do=manage&act=user&type=draft',
                        'menu_account_manage'=>'account&do=manage',
                        'menu_account_edit'=>'account&do=edit',
                ),
                'menu_groups_manage'=>'groups&do=manage',
//		'menu_group_add'=>'group&do=add',
//		'menu_account_profile'=>'account&do=profile',
        ),
//	'database'=>array(),
        'extend'=>array(
//                'menu_field_manage' => 'fields&do=manage',
				'menu_plugin_manage' => 'plugins&do=manage',
//                array(
//	                'menu_models_manage' => 'models&do=manage',
//	                'menu_models_add' => 'models&do=add',
//                ),
//		'menu_modifier_manage' => 'modifier&do=manage',
        ),
        'html'=>array(
                'menu_html_all'=>'html&do=all',
                'menu_html_index'=>'html&do=index',
                'menu_html_forum'=>'html&do=forum',
                'menu_html_article'=>'html&do=article',
                'menu_html_tag'=>'html&do=tag',
//                'menu_html_page'=>'html&do=page',
                'menu_setting_url'=>'setting&do=url',
        ),
        'tools'=>array(
                array(
                        'menu_link'=>'link',
                        'menu_link_add'=>'link&do=add',
                ),
                array(
                        'menu_advertise'=>'advertise',
                        'menu_advertise_add'=>'advertise&do=add',
                ),
                 array(
                       'menu_file_manage'=>'files&do=manage&method=database',
                       'menu_file_upload'=>'files&do=upload',
                ),
                'menu_extract_pic'=>'files&do=extract',
//                'menu_message'=>'message',
                'menu_cache'=>'cache',
                'menu_template_manage'=>'templates&do=manage',
                array(
	                'menu_database_backup'=>'database&do=backup',
	                'menu_database_recover'=>'database&do=recover',
                 ),
                array(
	               'menu_database_repair'=>'database&do=repair',
	                'menu_database_replace'=>'database&do=replace',
                ),
//		'menu_logs_admin'=>'logs',
        )
);
?>