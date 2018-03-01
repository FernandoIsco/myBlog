/*
Navicat MySQL Data Transfer

Source Server         : linux
Source Server Version : 50614
Source Host           : 192.168.22.213:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50614
File Encoding         : 65001

Date: 2018-03-01 13:37:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blogs
-- ----------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `summary` varchar(200) NOT NULL,
  `content` mediumtext NOT NULL,
  `cat_id` int(11) NOT NULL,
  `pic` varchar(500) DEFAULT NULL,
  `click` int(11) DEFAULT '0',
  `comments` int(11) DEFAULT '0',
  `is_delete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blogs
-- ----------------------------
INSERT INTO `blogs` VALUES ('2', '12', 'title123', 'hello world', 'hello world', '0', null, '0', '0', '0', '2017-12-21 14:58:45');
INSERT INTO `blogs` VALUES ('4', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 14:58:45');
INSERT INTO `blogs` VALUES ('6', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 15:04:51');
INSERT INTO `blogs` VALUES ('7', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 15:05:27');
INSERT INTO `blogs` VALUES ('8', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 15:05:46');
INSERT INTO `blogs` VALUES ('9', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 15:06:12');
INSERT INTO `blogs` VALUES ('10', '12', 'I am a boy', 'I am a boy', '111', '0', null, '0', '0', '0', '2017-12-21 15:06:56');
INSERT INTO `blogs` VALUES ('11', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('12', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('13', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('14', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('15', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('16', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('17', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('18', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('19', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('20', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('21', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('22', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('23', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('24', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('25', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('26', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('27', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('28', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:02:46');
INSERT INTO `blogs` VALUES ('30', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('31', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('32', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('33', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('34', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:06:40');
INSERT INTO `blogs` VALUES ('36', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('37', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('38', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('39', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('40', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:07:09');
INSERT INTO `blogs` VALUES ('42', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('43', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('44', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:10:42');
INSERT INTO `blogs` VALUES ('46', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('47', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('48', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('49', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:11:23');
INSERT INTO `blogs` VALUES ('51', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('52', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('53', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:11:51');
INSERT INTO `blogs` VALUES ('55', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('56', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('57', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('58', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:14:37');
INSERT INTO `blogs` VALUES ('60', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('61', '1', 'hello', 'hello', 'hello', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('62', '1', 'world', 'hello', 'hello', '0', null, '0', '0', '0', '2018-01-22 14:14:56');
INSERT INTO `blogs` VALUES ('64', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('65', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('66', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-22 14:16:46');
INSERT INTO `blogs` VALUES ('68', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('69', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `blogs` VALUES ('70', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-22 14:19:31');
INSERT INTO `blogs` VALUES ('71', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-22 14:19:56');
INSERT INTO `blogs` VALUES ('72', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '2018-01-22 14:19:56');
INSERT INTO `blogs` VALUES ('73', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-22 14:19:56');
INSERT INTO `blogs` VALUES ('75', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-22 14:37:04');
INSERT INTO `blogs` VALUES ('76', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '2018-01-22 14:37:04');
INSERT INTO `blogs` VALUES ('77', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-22 14:37:04');
INSERT INTO `blogs` VALUES ('78', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-29 10:54:25');
INSERT INTO `blogs` VALUES ('79', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '2018-01-29 10:54:25');
INSERT INTO `blogs` VALUES ('80', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-29 10:54:25');
INSERT INTO `blogs` VALUES ('82', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-29 11:09:45');
INSERT INTO `blogs` VALUES ('83', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '2018-01-29 11:09:45');
INSERT INTO `blogs` VALUES ('84', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-29 11:09:45');
INSERT INTO `blogs` VALUES ('86', '1', 'testInDatabase', 'testInDatabase', 'testInDatabase', '0', null, '0', '0', '0', '2018-01-29 15:39:59');
INSERT INTO `blogs` VALUES ('87', '1', 'testInsert', 'testInsert', 'testInsert', '0', null, '0', '0', '0', '2018-01-29 15:39:59');
INSERT INTO `blogs` VALUES ('88', '1', 'world', 'testUpdate', 'testUpdate', '0', null, '0', '0', '0', '2018-01-29 15:39:59');

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `is_delete` tinyint(4) DEFAULT '0' COMMENT '是否删除 0 否 1 是',
  `blogs` int(11) DEFAULT '0' COMMENT '文章数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_image` varchar(500) NOT NULL,
  `content` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_show` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES ('1', '1', '13', 'isco', '', '1111112121', '2017-12-20 18:19:05', '1');
INSERT INTO `comments` VALUES ('2', '1', '13', 'isco', '', '1111112121', '2017-12-20 18:19:05', '1');

-- ----------------------------
-- Table structure for document
-- ----------------------------
DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '标题',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上一层id',
  `href` varchar(128) NOT NULL DEFAULT '' COMMENT '链接',
  `content` text COMMENT 'markdown原始编码',
  `render` text COMMENT 'markdown解析后代码',
  `last_modify` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of document
-- ----------------------------
INSERT INTO `document` VALUES ('1', '序言', '0', '#', '## 序言\n个人练习和尝试做的框架，有问题可以联系`604625124@qq.com`\n欢迎修改开发，[github项目地址](https://github.com/FernandoIsco/Emilia)', '<h2>序言</h2>\n<p>个人练习和尝试做的框架，有问题可以联系<code>604625124@qq.com</code><br />\n欢迎修改开发，<a href=\"https://github.com/FernandoIsco/Emilia\" target=\"_blank\">github项目地址</a></p>\n', '1519876912');
INSERT INTO `document` VALUES ('2', '基础', '0', '#/base', 'For a detailed explanation on how things work, check out the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).\n\n\n# mavonEditor\n\n![npm](https://nodei.co/npm/mavon-editor.png?downloads=true&downloadRank=true&stars=true)\n\n> 基于Vue的markdown编辑器\n\n### [English Documents](./README-EN.md)\n[Demo for jsfiddle](https://jsfiddle.net/CHENXCHEN/qf7gLw3a/3/)\n\n## example (图片展示)\n\n### PC\n\n![PC](./img/cn/cn-common.png)\n\n![PC](./img/cn/cn-image.gif)\n\n> [查看更多图片点击这里...](./doc/cn/images.md)\n\n### Install mavon-editor (安装)\n\n```\n$ npm install mavon-editor --save\n```\n\n### Use (如何引入)\n\n`index.js`:\n```javascript\n    // 全局注册\n    // import with ES6\n    import Vue from \'vue\'\n    import mavonEditor from \'mavon-editor\'\n    import \'mavon-editor/dist/css/index.css\'\n    // use\n    Vue.use(mavonEditor)\n    new Vue({\n        \'el\': \'#main\',\n        data() {\n            return { value: \'\' }\n        }\n    })\n```\n`index.html`\n```html\n<div id=\"main\">\n    <mavon-editor v-model=\"value\"/>\n</div>\n```\n\n> [更多引入方式点击这里...](./doc/cn/use.md)\n\n> [如何获取并设置markdown-it对象...](./doc/cn/markdown.md)\n\n#### 代码高亮\n\n> 如不需要hightlight代码高亮显示，你应该设置ishljs为false\n\n开启代码高亮props\n```javascript\n    // ishljs默认为true\n    <mavon-editor :ishljs = \"true\"></mavon-editor>\n```\n\n为优化插件体积，从**v2.4.2**起以下文件将默认使用`cdnjs`外链:\n + `highlight.js`\n + `github-markdown-css`\n + `katex`(**v2.4.7**)\n\n代码高亮`highlight.js`中的语言解析文件和代码高亮样式将在使用时按需加载.\n`github-markdown-css`和`katex`仅会在`mounted`时加载\n\n**Notice**:\n[可选配色方案](./src/lib/core/hljs/lang.hljs.css.js) 和 [支持的语言](./src/lib/core/hljs/lang.hljs.js) 是从 [highlight.js/9.12.0](https://github.com/isagalaev/highlight.js/tree/master/src) 导出的\n\n> [不使用cdn，本地按需加载点击这里...](./doc/cn/no-cnd.md)\n\n#### 图片上传\n\n```javascript\n<template>\n    <mavon-editor ref=md @imgAdd=\"$imgAdd\" @imgDel=\"$imgDel\"></mavon-editor>\n</template>\nexports default {\n    methods: {\n        // 绑定@imgAdd event\n        $imgAdd(pos, $file){\n            // 第一步.将图片上传到服务器.\n           var formdata = new FormData();\n           formdata.append(\'image\', $file);\n           axios({\n               url: \'server url\',\n               method: \'post\',\n               data: formdata,\n               headers: { \'Content-Type\': \'multipart/form-data\' },\n           }).then((url) => {\n               // 第二步.将返回的url替换到文本原位置![...](./0) -> ![...](url)\n               /**\n               * $vm 指为mavonEditor实例，可以通过如下两种方式获取\n               * 1. 通过引入对象获取: `import {mavonEditor} from ...` 等方式引入后，`$vm`为`mavonEditor`\n               * 2. 通过$refs获取: html声明ref : `<mavon-editor ref=md ></mavon-editor>，`$vm`为 `this.$refs.md`\n               */\n               $vm.$img2Url(pos, url);\n           })\n        }\n    }\n}\n```\n> [图片上传详情点击这里...](./doc/cn/upload-images.md)\n\n### 注\n\n- **默认大小样式为 min-height: 300px , min-width: 300px 可自行覆盖**\n- **基础z-index: 1500**\n- **仅用作展示可以设置props: toolbarsFlag: false , subfield: false, default_open: \"preview\"**\n\n## API 文档\n\n### props\n\n| name 名称      | type 类型 | default 默认值 | describe 描述                          |\n| ------------ | :-----: | :---------: | ---------------------------------------- |\n| value        | String  |             | 初始值                                     |\n| language     | String  |     cn      | 语言选择，暂支持 cn: 中文简体 ， en: 英文 ， fr: 法语 |\n| scrollStyle  | Boolean |    true     | 开启滚动条样式(暂时仅支持chrome)              |\n| subfield     | Boolean |    true     | true： 双栏(编辑预览同屏)， false： 单栏(编辑预览分屏)    |\n| default_open | String |         | edit： 默认展示编辑区域 ， preview： 默认展示预览区域  , 其他 = edit |\n| placeholder | String |    开始编辑...     |  输入框为空时默认提示文本  |\n| editable     | Boolean |    true     | 是否允许编辑     |\n| code_style | String |    code-github     | markdown样式： 默认github, [可选配色方案](./src/lib/core/hljs/lang.hljs.css.js)   |\n| toolbarsFlag | Boolean |    true     | 工具栏是否显示                |\n| toolbars     | Object  |     如下例     | 工具栏                      |\n| ishljs       | Boolean |     true     |  代码高亮 |\n| image_filter | function |     null     |  图片过滤函数，参数为一个`File Object`，要求返回一个`Boolean`, `true`表示文件合法，`false`表示文件不合法 |\n\n```javascript\n /*\n    默认工具栏按钮全部开启, 传入自定义对象\n    例如: {\n         bold: true, // 粗体\n         italic: true,// 斜体\n         header: true,// 标题\n    }\n    此时, 仅仅显示此三个功能键\n */\ntoolbars: {\n      bold: true, // 粗体\n      italic: true, // 斜体\n      header: true, // 标题\n      underline: true, // 下划线\n      strikethrough: true, // 中划线\n      mark: true, // 标记\n      superscript: true, // 上角标\n      subscript: true, // 下角标\n      quote: true, // 引用\n      ol: true, // 有序列表\n      ul: true, // 无序列表\n      link: true, // 链接\n      imagelink: true, // 图片链接\n      code: true, // code\n      table: true, // 表格\n      fullscreen: true, // 全屏编辑\n      readmodel: true, // 沉浸式阅读\n      htmlcode: true, // 展示html源码\n      help: true, // 帮助\n      /* 1.3.5 */\n      undo: true, // 上一步\n      redo: true, // 下一步\n      trash: true, // 清空\n      save: true, // 保存（触发events中的save事件）\n      /* 1.4.2 */\n      navigation: true, // 导航目录\n      /* 2.1.8 */\n      alignleft: true, // 左对齐\n      aligncenter: true, // 居中\n      alignright: true, // 右对齐\n      /* 2.2.1 */\n      subfield: true, // 单双栏模式\n      preview: true, // 预览\n  }\n```\n\n### events\n\n| name 方法名         |            params 参数            | describe 描述                              |\n| ---------------- | :-----------------------------: | ---------------------------------------- |\n| change           |  String: value , String: render  | 编辑区发生变化的回调事件(render: value 经过markdown解析后的结果) |\n| save             |  String: value , String: render  | ctrl + s 的回调事件(保存按键,同样触发该回调)             |\n| fullscreen       | Boolean: status , String: value | 切换全屏编辑的回调事件(boolean: 全屏开启状态)             |\n| readmodel        | Boolean: status , String: value | 切换沉浸式阅读的回调事件(boolean: 阅读开启状态)            |\n| htmlcode         | Boolean: status , String: value | 查看html源码的回调事件(boolean: 源码开启状态)           |\n| subfieldtoggle   | Boolean: status , String: value | 切换单双栏编辑的回调事件(boolean: 双栏开启状态)            |\n| previewtoggle   | Boolean: status , String: value | 切换预览编辑的回调事件(boolean: 预览开启状态)            |\n| helptoggle       | Boolean: status , String: value | 查看帮助的回调事件(boolean: 帮助开启状态)               |\n| navigationtoggle | Boolean: status , String: value | 切换导航目录的回调事件(boolean: 导航开启状态)             |\n| imgAdd           | String: filename, File: imgfile | 图片文件添加回调事件(filename: 写在md中的文件名, File: File Object) |\n| imgDel           |        String: filename         | 图片文件删除回调事件(filename: 写在md中的文件名)          |\n\n\n## Dependencies (依赖)\n\n- [markdown-it](https://github.com/markdown-it/markdown-it)\n\n- [auto-textarea](https://github.com/hinesboy/auto-textarea)\n\n- [stylus](https://github.com/stylus/stylus)\n\n\n## update(更新内容)\n\n- [更新日志](./LOG.md)\n\n## Collaborators(合作者)\n\n- [CHENXCHEN](https://github.com/CHENXCHEN)\n\n\n## Licence (证书)\n\nmavonEditor is open source and released under the MIT Licence.\n\nCopyright (c) 2017 hinesboy', '<p>For a detailed explanation on how things work, check out the <a href=\"http://vuejs-templates.github.io/webpack/\" target=\"_blank\">guide</a> and <a href=\"http://vuejs.github.io/vue-loader\" target=\"_blank\">docs for vue-loader</a>.</p>\n<h1>mavonEditor</h1>\n<p><img src=\"https://nodei.co/npm/mavon-editor.png?downloads=true&amp;downloadRank=true&amp;stars=true\" alt=\"npm\" /></p>\n<blockquote>\n<p>基于Vue的markdown编辑器</p>\n</blockquote>\n<h3><a href=\"./README-EN.md\" target=\"_blank\">English Documents</a></h3>\n<p><a href=\"https://jsfiddle.net/CHENXCHEN/qf7gLw3a/3/\" target=\"_blank\">Demo for jsfiddle</a></p>\n<h2>example (图片展示)</h2>\n<h3>PC</h3>\n<p><img src=\"./img/cn/cn-common.png\" alt=\"PC\" /></p>\n<p><img src=\"./img/cn/cn-image.gif\" alt=\"PC\" /></p>\n<blockquote>\n<p><a href=\"./doc/cn/images.md\" target=\"_blank\">查看更多图片点击这里…</a></p>\n</blockquote>\n<h3>Install mavon-editor (安装)</h3>\n<pre><code class=\"lang-\">$ npm install mavon-editor --save\n</code></pre>\n<h3>Use (如何引入)</h3>\n<p><code>index.js</code>:</p>\n<pre><div class=\"hljs\"><code class=\"lang-javascript\">    <span class=\"hljs-comment\">// 全局注册</span>\n    <span class=\"hljs-comment\">// import with ES6</span>\n    <span class=\"hljs-keyword\">import</span> Vue <span class=\"hljs-keyword\">from</span> <span class=\"hljs-string\">\'vue\'</span>\n    <span class=\"hljs-keyword\">import</span> mavonEditor <span class=\"hljs-keyword\">from</span> <span class=\"hljs-string\">\'mavon-editor\'</span>\n    <span class=\"hljs-keyword\">import</span> <span class=\"hljs-string\">\'mavon-editor/dist/css/index.css\'</span>\n    <span class=\"hljs-comment\">// use</span>\n    Vue.use(mavonEditor)\n    <span class=\"hljs-keyword\">new</span> Vue({\n        <span class=\"hljs-string\">\'el\'</span>: <span class=\"hljs-string\">\'#main\'</span>,\n        data() {\n            <span class=\"hljs-keyword\">return</span> { <span class=\"hljs-attr\">value</span>: <span class=\"hljs-string\">\'\'</span> }\n        }\n    })\n</code></div></pre>\n<p><code>index.html</code></p>\n<pre><div class=\"hljs\"><code class=\"lang-html\"><span class=\"hljs-tag\">&lt;<span class=\"hljs-name\">div</span> <span class=\"hljs-attr\">id</span>=<span class=\"hljs-string\">\"main\"</span>&gt;</span>\n    <span class=\"hljs-tag\">&lt;<span class=\"hljs-name\">mavon-editor</span> <span class=\"hljs-attr\">v-model</span>=<span class=\"hljs-string\">\"value\"</span>/&gt;</span>\n<span class=\"hljs-tag\">&lt;/<span class=\"hljs-name\">div</span>&gt;</span>\n</code></div></pre>\n<blockquote>\n<p><a href=\"./doc/cn/use.md\" target=\"_blank\">更多引入方式点击这里…</a></p>\n</blockquote>\n<blockquote>\n<p><a href=\"./doc/cn/markdown.md\" target=\"_blank\">如何获取并设置markdown-it对象…</a></p>\n</blockquote>\n<h4>代码高亮</h4>\n<blockquote>\n<p>如不需要hightlight代码高亮显示，你应该设置ishljs为false</p>\n</blockquote>\n<p>开启代码高亮props</p>\n<pre><div class=\"hljs\"><code class=\"lang-javascript\">    <span class=\"hljs-comment\">// ishljs默认为true</span>\n    &lt;mavon-editor :ishljs = <span class=\"hljs-string\">\"true\"</span>&gt;<span class=\"xml\"><span class=\"hljs-tag\">&lt;/<span class=\"hljs-name\">mavon-editor</span>&gt;</span>\n</span></code></div></pre>\n<p>为优化插件体积，从<strong>v2.4.2</strong>起以下文件将默认使用<code>cdnjs</code>外链:</p>\n<ul>\n<li><code>highlight.js</code></li>\n<li><code>github-markdown-css</code></li>\n<li><code>katex</code>(<strong>v2.4.7</strong>)</li>\n</ul>\n<p>代码高亮<code>highlight.js</code>中的语言解析文件和代码高亮样式将在使用时按需加载.<br />\n<code>github-markdown-css</code>和<code>katex</code>仅会在<code>mounted</code>时加载</p>\n<p><strong>Notice</strong>:<br />\n<a href=\"./src/lib/core/hljs/lang.hljs.css.js\" target=\"_blank\">可选配色方案</a> 和 <a href=\"./src/lib/core/hljs/lang.hljs.js\" target=\"_blank\">支持的语言</a> 是从 <a href=\"https://github.com/isagalaev/highlight.js/tree/master/src\" target=\"_blank\">highlight.js/9.12.0</a> 导出的</p>\n<blockquote>\n<p><a href=\"./doc/cn/no-cnd.md\" target=\"_blank\">不使用cdn，本地按需加载点击这里…</a></p>\n</blockquote>\n<h4>图片上传</h4>\n<pre><div class=\"hljs\"><code class=\"lang-javascript\">&lt;template&gt;\n    <span class=\"xml\"><span class=\"hljs-tag\">&lt;<span class=\"hljs-name\">mavon-editor</span> <span class=\"hljs-attr\">ref</span>=<span class=\"hljs-string\">md</span> @<span class=\"hljs-attr\">imgAdd</span>=<span class=\"hljs-string\">\"$imgAdd\"</span> @<span class=\"hljs-attr\">imgDel</span>=<span class=\"hljs-string\">\"$imgDel\"</span>&gt;</span><span class=\"hljs-tag\">&lt;/<span class=\"hljs-name\">mavon-editor</span>&gt;</span>\n<span class=\"hljs-tag\">&lt;/<span class=\"hljs-name\">template</span>&gt;</span></span>\nexports <span class=\"hljs-keyword\">default</span> {\n    <span class=\"hljs-attr\">methods</span>: {\n        <span class=\"hljs-comment\">// 绑定@imgAdd event</span>\n        $imgAdd(pos, $file){\n            <span class=\"hljs-comment\">// 第一步.将图片上传到服务器.</span>\n           <span class=\"hljs-keyword\">var</span> formdata = <span class=\"hljs-keyword\">new</span> FormData();\n           formdata.append(<span class=\"hljs-string\">\'image\'</span>, $file);\n           axios({\n               <span class=\"hljs-attr\">url</span>: <span class=\"hljs-string\">\'server url\'</span>,\n               <span class=\"hljs-attr\">method</span>: <span class=\"hljs-string\">\'post\'</span>,\n               <span class=\"hljs-attr\">data</span>: formdata,\n               <span class=\"hljs-attr\">headers</span>: { <span class=\"hljs-string\">\'Content-Type\'</span>: <span class=\"hljs-string\">\'multipart/form-data\'</span> },\n           }).then(<span class=\"hljs-function\">(<span class=\"hljs-params\">url</span>) =&gt;</span> {\n               <span class=\"hljs-comment\">// 第二步.将返回的url替换到文本原位置![...](./0) -&gt; ![...](url)</span>\n               <span class=\"hljs-comment\">/**\n               * $vm 指为mavonEditor实例，可以通过如下两种方式获取\n               * 1. 通过引入对象获取: `import {mavonEditor} from ...` 等方式引入后，`$vm`为`mavonEditor`\n               * 2. 通过$refs获取: html声明ref : `&lt;mavon-editor ref=md &gt;&lt;/mavon-editor&gt;，`$vm`为 `this.$refs.md`\n               */</span>\n               $vm.$img2Url(pos, url);\n           })\n        }\n    }\n}\n</code></div></pre>\n<blockquote>\n<p><a href=\"./doc/cn/upload-images.md\" target=\"_blank\">图片上传详情点击这里…</a></p>\n</blockquote>\n<h3>注</h3>\n<ul>\n<li><strong>默认大小样式为 min-height: 300px , min-width: 300px 可自行覆盖</strong></li>\n<li><strong>基础z-index: 1500</strong></li>\n<li><strong>仅用作展示可以设置props: toolbarsFlag: false , subfield: false, default_open: &quot;preview&quot;</strong></li>\n</ul>\n<h2>API 文档</h2>\n<h3>props</h3>\n<table>\n<thead>\n<tr>\n<th>name 名称</th>\n<th style=\"text-align:center\">type 类型</th>\n<th style=\"text-align:center\">default 默认值</th>\n<th>describe 描述</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>value</td>\n<td style=\"text-align:center\">String</td>\n<td style=\"text-align:center\"></td>\n<td>初始值</td>\n</tr>\n<tr>\n<td>language</td>\n<td style=\"text-align:center\">String</td>\n<td style=\"text-align:center\">cn</td>\n<td>语言选择，暂支持 cn: 中文简体 ， en: 英文 ， fr: 法语</td>\n</tr>\n<tr>\n<td>scrollStyle</td>\n<td style=\"text-align:center\">Boolean</td>\n<td style=\"text-align:center\">true</td>\n<td>开启滚动条样式(暂时仅支持chrome)</td>\n</tr>\n<tr>\n<td>subfield</td>\n<td style=\"text-align:center\">Boolean</td>\n<td style=\"text-align:center\">true</td>\n<td>true： 双栏(编辑预览同屏)， false： 单栏(编辑预览分屏)</td>\n</tr>\n<tr>\n<td>default_open</td>\n<td style=\"text-align:center\">String</td>\n<td style=\"text-align:center\"></td>\n<td>edit： 默认展示编辑区域 ， preview： 默认展示预览区域  , 其他 = edit</td>\n</tr>\n<tr>\n<td>placeholder</td>\n<td style=\"text-align:center\">String</td>\n<td style=\"text-align:center\">开始编辑…</td>\n<td>输入框为空时默认提示文本</td>\n</tr>\n<tr>\n<td>editable</td>\n<td style=\"text-align:center\">Boolean</td>\n<td style=\"text-align:center\">true</td>\n<td>是否允许编辑</td>\n</tr>\n<tr>\n<td>code_style</td>\n<td style=\"text-align:center\">String</td>\n<td style=\"text-align:center\">code-github</td>\n<td>markdown样式： 默认github, <a href=\"./src/lib/core/hljs/lang.hljs.css.js\" target=\"_blank\">可选配色方案</a></td>\n</tr>\n<tr>\n<td>toolbarsFlag</td>\n<td style=\"text-align:center\">Boolean</td>\n<td style=\"text-align:center\">true</td>\n<td>工具栏是否显示</td>\n</tr>\n<tr>\n<td>toolbars</td>\n<td style=\"text-align:center\">Object</td>\n<td style=\"text-align:center\">如下例</td>\n<td>工具栏</td>\n</tr>\n<tr>\n<td>ishljs</td>\n<td style=\"text-align:center\">Boolean</td>\n<td style=\"text-align:center\">true</td>\n<td>代码高亮</td>\n</tr>\n<tr>\n<td>image_filter</td>\n<td style=\"text-align:center\">function</td>\n<td style=\"text-align:center\">null</td>\n<td>图片过滤函数，参数为一个<code>File Object</code>，要求返回一个<code>Boolean</code>, <code>true</code>表示文件合法，<code>false</code>表示文件不合法</td>\n</tr>\n</tbody>\n</table>\n<pre><div class=\"hljs\"><code class=\"lang-javascript\"> <span class=\"hljs-comment\">/*\n    默认工具栏按钮全部开启, 传入自定义对象\n    例如: {\n         bold: true, // 粗体\n         italic: true,// 斜体\n         header: true,// 标题\n    }\n    此时, 仅仅显示此三个功能键\n */</span>\ntoolbars: {\n      <span class=\"hljs-attr\">bold</span>: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 粗体</span>\n      italic: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 斜体</span>\n      header: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 标题</span>\n      underline: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 下划线</span>\n      strikethrough: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 中划线</span>\n      mark: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 标记</span>\n      superscript: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 上角标</span>\n      subscript: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 下角标</span>\n      quote: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 引用</span>\n      ol: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 有序列表</span>\n      ul: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 无序列表</span>\n      link: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 链接</span>\n      imagelink: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 图片链接</span>\n      code: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// code</span>\n      table: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 表格</span>\n      fullscreen: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 全屏编辑</span>\n      readmodel: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 沉浸式阅读</span>\n      htmlcode: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 展示html源码</span>\n      help: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 帮助</span>\n      <span class=\"hljs-comment\">/* 1.3.5 */</span>\n      undo: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 上一步</span>\n      redo: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 下一步</span>\n      trash: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 清空</span>\n      save: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 保存（触发events中的save事件）</span>\n      <span class=\"hljs-comment\">/* 1.4.2 */</span>\n      navigation: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 导航目录</span>\n      <span class=\"hljs-comment\">/* 2.1.8 */</span>\n      alignleft: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 左对齐</span>\n      aligncenter: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 居中</span>\n      alignright: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 右对齐</span>\n      <span class=\"hljs-comment\">/* 2.2.1 */</span>\n      subfield: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 单双栏模式</span>\n      preview: <span class=\"hljs-literal\">true</span>, <span class=\"hljs-comment\">// 预览</span>\n  }\n</code></div></pre>\n<h3>events</h3>\n<table>\n<thead>\n<tr>\n<th>name 方法名</th>\n<th style=\"text-align:center\">params 参数</th>\n<th>describe 描述</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>change</td>\n<td style=\"text-align:center\">String: value , String: render</td>\n<td>编辑区发生变化的回调事件(render: value 经过markdown解析后的结果)</td>\n</tr>\n<tr>\n<td>save</td>\n<td style=\"text-align:center\">String: value , String: render</td>\n<td>ctrl + s 的回调事件(保存按键,同样触发该回调)</td>\n</tr>\n<tr>\n<td>fullscreen</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>切换全屏编辑的回调事件(boolean: 全屏开启状态)</td>\n</tr>\n<tr>\n<td>readmodel</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>切换沉浸式阅读的回调事件(boolean: 阅读开启状态)</td>\n</tr>\n<tr>\n<td>htmlcode</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>查看html源码的回调事件(boolean: 源码开启状态)</td>\n</tr>\n<tr>\n<td>subfieldtoggle</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>切换单双栏编辑的回调事件(boolean: 双栏开启状态)</td>\n</tr>\n<tr>\n<td>previewtoggle</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>切换预览编辑的回调事件(boolean: 预览开启状态)</td>\n</tr>\n<tr>\n<td>helptoggle</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>查看帮助的回调事件(boolean: 帮助开启状态)</td>\n</tr>\n<tr>\n<td>navigationtoggle</td>\n<td style=\"text-align:center\">Boolean: status , String: value</td>\n<td>切换导航目录的回调事件(boolean: 导航开启状态)</td>\n</tr>\n<tr>\n<td>imgAdd</td>\n<td style=\"text-align:center\">String: filename, File: imgfile</td>\n<td>图片文件添加回调事件(filename: 写在md中的文件名, File: File Object)</td>\n</tr>\n<tr>\n<td>imgDel</td>\n<td style=\"text-align:center\">String: filename</td>\n<td>图片文件删除回调事件(filename: 写在md中的文件名)</td>\n</tr>\n</tbody>\n</table>\n<h2>Dependencies (依赖)</h2>\n<ul>\n<li>\n<p><a href=\"https://github.com/markdown-it/markdown-it\" target=\"_blank\">markdown-it</a></p>\n</li>\n<li>\n<p><a href=\"https://github.com/hinesboy/auto-textarea\" target=\"_blank\">auto-textarea</a></p>\n</li>\n<li>\n<p><a href=\"https://github.com/stylus/stylus\" target=\"_blank\">stylus</a></p>\n</li>\n</ul>\n<h2>update(更新内容)</h2>\n<ul>\n<li><a href=\"./LOG.md\" target=\"_blank\">更新日志</a></li>\n</ul>\n<h2>Collaborators(合作者)</h2>\n<ul>\n<li><a href=\"https://github.com/CHENXCHEN\" target=\"_blank\">CHENXCHEN</a></li>\n</ul>\n<h2>Licence (证书)</h2>\n<p>mavonEditor is open source and released under the MIT Licence.</p>\n<p>Copyright © 2017 hinesboy</p>\n', '1519876909');
INSERT INTO `document` VALUES ('3', '配置', '0', '#/config', 'config', 'config', null);
INSERT INTO `document` VALUES ('4', '下载', '2', '#/download', '### download122111dddd', '<h3>download122111dddd</h3>\n', '1519875200');
INSERT INTO `document` VALUES ('5', '目录结构', '2', '#/config', '目录结构', '目录结构', null);
INSERT INTO `document` VALUES ('6', '开发规范', '2', '', '开发规范', '开发规范', null);
INSERT INTO `document` VALUES ('7', '读取配置', '3', '', '读取配置', '读取配置', null);
INSERT INTO `document` VALUES ('8', '扩展配置', '3', '', '扩展配置', '扩展配置', null);
INSERT INTO `document` VALUES ('9', '路由', '0', '', '路由', '路由', null);
INSERT INTO `document` VALUES ('10', 'url模式', '9', '', 'url模式', 'url模式', null);
INSERT INTO `document` VALUES ('11', '路由定义', '9', '', '路由定义', '路由定义', null);
INSERT INTO `document` VALUES ('12', '闭包支持', '9', '', '闭包支持', '闭包支持', null);
INSERT INTO `document` VALUES ('13', '控制器', '0', '', '控制器', '控制器', null);
INSERT INTO `document` VALUES ('14', '控制器定义', '13', '', '控制器定义', '控制器定义', null);
INSERT INTO `document` VALUES ('15', '跳转和重定向', '13', '', '跳转和重定向', '跳转和重定向', null);
INSERT INTO `document` VALUES ('16', '异步返回', '13', '', '异步返回', '异步返回', null);
INSERT INTO `document` VALUES ('17', '数据库', '0', '', '数据库', '数据库', null);
INSERT INTO `document` VALUES ('18', '数据库配置', '17', '', '数据库配置', '数据库配置', null);
INSERT INTO `document` VALUES ('19', '基本使用', '17', '', '基本使用', '基本使用', null);
INSERT INTO `document` VALUES ('20', '语句构造', '17', '', '语句构造', '语句构造', null);
INSERT INTO `document` VALUES ('21', '事务操作', '17', '', '事务操作', '事务操作', null);
INSERT INTO `document` VALUES ('22', '模型', '0', '', '模型', '模型', null);
INSERT INTO `document` VALUES ('23', '模型定义', '22', '', '模型定义', '模型定义', null);
INSERT INTO `document` VALUES ('24', '使用方法', '22', '', '使用方法', '使用方法', null);
INSERT INTO `document` VALUES ('25', '视图', '0', '', '视图', '视图', null);
INSERT INTO `document` VALUES ('26', '模板渲染', '25', '', '模板渲染', '模板渲染', null);
INSERT INTO `document` VALUES ('27', '模板赋值', '25', '', '模板赋值', '模板赋值', null);
INSERT INTO `document` VALUES ('28', '模板布局', '25', '', '模板布局', '模板布局', null);
INSERT INTO `document` VALUES ('29', '日志', '0', '', '日志', '日志', null);
INSERT INTO `document` VALUES ('30', '日志驱动', '29', '', '日志驱动', '日志驱动', null);
INSERT INTO `document` VALUES ('31', '写入日志', '29', '', '写入日志', '写入日志', null);
INSERT INTO `document` VALUES ('32', '其它', '0', '', '其它', '其它', null);
INSERT INTO `document` VALUES ('33', '输入安全', '32', '', '输入安全', '输入安全', null);
INSERT INTO `document` VALUES ('34', '缓存', '32', '', '缓存', '缓存', null);
INSERT INTO `document` VALUES ('35', 'cookie', '32', '', 'cookie', 'cookie', null);
INSERT INTO `document` VALUES ('36', 'session', '32', '', 'session', 'session', null);
INSERT INTO `document` VALUES ('37', '单元测试', '32', '', '单元测试', '单元测试', null);

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `log_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(8) DEFAULT NULL,
  `content` text,
  `file` varchar(255) DEFAULT NULL,
  `line` smallint(3) DEFAULT NULL,
  `class` varchar(64) DEFAULT NULL,
  `method` varchar(64) DEFAULT NULL,
  `createtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logs
-- ----------------------------
INSERT INTO `logs` VALUES ('2', '*', 'test', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '34', 'vendor\\framework\\foundation\\Application', null, '2017-11-30 14:26:54');
INSERT INTO `logs` VALUES ('3', '*', 'test11', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '34', 'vendor\\framework\\foundation\\Application', null, '2017-11-30 14:27:25');
INSERT INTO `logs` VALUES ('4', '*', 'i am an object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '36', 'vendor\\framework\\foundation\\Application', null, '2017-11-30 14:30:28');
INSERT INTO `logs` VALUES ('5', '*', 'i am an object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '36', null, null, '2017-11-30 14:31:58');
INSERT INTO `logs` VALUES ('6', '*', 'i am an object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '36', 'vendor\\framework\\foundation\\Application', null, '2017-11-30 14:45:07');
INSERT INTO `logs` VALUES ('7', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', '', '', '2017-12-12 18:46:38');
INSERT INTO `logs` VALUES ('8', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:49:01');
INSERT INTO `logs` VALUES ('9', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:49:43');
INSERT INTO `logs` VALUES ('10', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:51:06');
INSERT INTO `logs` VALUES ('11', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:51:17');
INSERT INTO `logs` VALUES ('12', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:51:29');
INSERT INTO `logs` VALUES ('13', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:12');
INSERT INTO `logs` VALUES ('14', '*', '', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:12');
INSERT INTO `logs` VALUES ('15', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:37');
INSERT INTO `logs` VALUES ('16', '*', '', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:37');
INSERT INTO `logs` VALUES ('17', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:52');
INSERT INTO `logs` VALUES ('18', '*', '', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-12 18:54:52');
INSERT INTO `logs` VALUES ('19', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:07:09');
INSERT INTO `logs` VALUES ('20', '*', '', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:07:09');
INSERT INTO `logs` VALUES ('21', '*', '', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:07:57');
INSERT INTO `logs` VALUES ('22', '*', 'Fetal error: Call to undefined method vendor\\framework\\cache\\driver\\MemcachedStore::setMulti()', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '36', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:14:30');
INSERT INTO `logs` VALUES ('23', '*', 'Fetal error: Call to undefined method vendor\\framework\\cache\\driver\\MemcachedStore::multiSet()', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '36', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:16:37');
INSERT INTO `logs` VALUES ('24', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\exception\\Exception', 'errorHandler', '2017-12-13 09:17:01');
INSERT INTO `logs` VALUES ('25', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\exception\\Exception', '', '2017-12-13 09:24:00');
INSERT INTO `logs` VALUES ('26', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\exception\\Exception', 'errorHandler', '2017-12-13 09:24:34');
INSERT INTO `logs` VALUES ('27', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\exception\\Exception', 'errorHandler', '2017-12-13 09:34:43');
INSERT INTO `logs` VALUES ('28', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/exception/Exception.php', '82', 'vendor\\framework\\exception\\Exception', 'errorHandler', '2017-12-13 09:36:03');
INSERT INTO `logs` VALUES ('29', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\cache\\driver\\MemcachedStore', 'get', '2017-12-13 09:38:13');
INSERT INTO `logs` VALUES ('30', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\cache\\driver\\MemcachedStore', 'get', '2017-12-13 09:39:26');
INSERT INTO `logs` VALUES ('31', '*', 'I am object', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '35', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-13 09:40:09');
INSERT INTO `logs` VALUES ('32', '*', 'Array to string conversion', '/apps/dat/web/working/miework-dev/vendor/framework/cache/driver/MemcachedStore.php', '42', 'vendor\\framework\\cache\\driver\\MemcachedStore', 'get', '2017-12-13 09:40:09');
INSERT INTO `logs` VALUES ('33', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:08:45');
INSERT INTO `logs` VALUES ('34', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:28:18');
INSERT INTO `logs` VALUES ('35', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:29:39');
INSERT INTO `logs` VALUES ('36', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:33:18');
INSERT INTO `logs` VALUES ('37', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:34:02');
INSERT INTO `logs` VALUES ('38', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:35:10');
INSERT INTO `logs` VALUES ('39', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:35:19');
INSERT INTO `logs` VALUES ('40', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '50', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:54:17');
INSERT INTO `logs` VALUES ('41', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 14:58:45');
INSERT INTO `logs` VALUES ('42', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:03:47');
INSERT INTO `logs` VALUES ('43', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:04:51');
INSERT INTO `logs` VALUES ('44', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:05:27');
INSERT INTO `logs` VALUES ('45', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:06:56');
INSERT INTO `logs` VALUES ('46', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:09:36');
INSERT INTO `logs` VALUES ('47', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:09:39');
INSERT INTO `logs` VALUES ('48', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:10:05');
INSERT INTO `logs` VALUES ('49', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:10:13');
INSERT INTO `logs` VALUES ('50', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:10:20');
INSERT INTO `logs` VALUES ('51', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:10:20');
INSERT INTO `logs` VALUES ('52', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:10:31');
INSERT INTO `logs` VALUES ('53', '*', 'test new query', '/apps/dat/web/working/miework-dev/app/index/controller/site.php', '66', 'app\\index\\controller\\Site', 'index', '2017-12-21 15:11:14');
INSERT INTO `logs` VALUES ('54', '*', 'test database log', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '34', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-26 10:02:43');
INSERT INTO `logs` VALUES ('55', '*', 'test database log', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '34', 'vendor\\framework\\foundation\\Application', 'run', '2017-12-26 10:03:47');
INSERT INTO `logs` VALUES ('56', '*', 'test database log', '/apps/dat/web/working/miework-dev/vendor/framework/foundation/Application.php', '34', 'vendorframeworkfoundationApplication', 'run', '2017-12-26 10:04:58');

-- ----------------------------
-- Table structure for session
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `session_id` char(32) NOT NULL,
  `session_data` varchar(255) DEFAULT NULL,
  `last_modify` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of session
-- ----------------------------
INSERT INTO `session` VALUES ('djgcagub0fq90tgner6hqks7c6', 'abc|s:3:\"123\";', '1519873123');
INSERT INTO `session` VALUES ('ednlt4acdsuprbkhebtvnkovl0', '', '1519809845');
INSERT INTO `session` VALUES ('tenvqfr0b78u5c1dtc892iek04', '', '1519876860');

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `token_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL COMMENT 'session_id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: 未登录 1 已登录',
  `last_modify` int(10) unsigned NOT NULL,
  `create_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of token
-- ----------------------------
INSERT INTO `token` VALUES ('121', 'ednlt4acdsuprbkhebtvnkovl0', '12', '0', '1519875193', '1519809845');
INSERT INTO `token` VALUES ('122', 'djgcagub0fq90tgner6hqks7c6', '12', '1', '1519882579', '1519867858');
INSERT INTO `token` VALUES ('123', 'tenvqfr0b78u5c1dtc892iek04', '0', '0', '1519876918', '1519876860');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员 1 是 0 否',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论次数',
  `favorable` varchar(128) DEFAULT NULL COMMENT '关注分类',
  `image` varchar(500) DEFAULT NULL COMMENT '图片',
  `ip` varchar(15) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_uindex` (`id`),
  UNIQUE KEY `user_name_uindex` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('12', 'fernando', 'e10adc3949ba59abbe56e057f20f883e', '1', '0', null, null, '192.168.22.195', '2017-11-17 10:58:40', '2017-11-17 10:58:40');
INSERT INTO `user` VALUES ('13', 'isco', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', null, null, '192.168.22.195', '2017-11-17 14:20:59', '2017-11-17 14:20:59');
