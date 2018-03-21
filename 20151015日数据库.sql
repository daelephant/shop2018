-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-15 11:20:41
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `php2018`
--

-- --------------------------------------------------------

--
-- 表的结构 `p2018_brand`
--

CREATE TABLE IF NOT EXISTS `p2018_brand` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `brand_name` varchar(30) NOT NULL COMMENT '品牌名称',
  `site_url` varchar(150) NOT NULL DEFAULT '' COMMENT '官方网址',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌Logo图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='品牌' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `p2018_brand`
--

INSERT INTO `p2018_brand` (`id`, `brand_name`, `site_url`, `logo`) VALUES
(2, '苹果', '', 'Brand/2015-10-13/561cc92ba6c33.jpg'),
(3, '小米', '', ''),
(4, '三星', '', ''),
(5, '华为', '', ''),
(6, '酷派', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `p2018_goods`
--

CREATE TABLE IF NOT EXISTS `p2018_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价格',
  `goods_desc` longtext COMMENT '商品描述',
  `is_on_sale` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否上架',
  `is_delete` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否放到回收站',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '原图',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '小图',
  `mid_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '中图',
  `big_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '大图',
  `mbig_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '更大图',
  `brand_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类id',
  PRIMARY KEY (`id`),
  KEY `shop_price` (`shop_price`),
  KEY `addtime` (`addtime`),
  KEY `brand_id` (`brand_id`),
  KEY `cat_id` (`cat_id`),
  KEY `is_on_sale` (`is_on_sale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `p2018_goods`
--

INSERT INTO `p2018_goods` (`id`, `goods_name`, `market_price`, `shop_price`, `goods_desc`, `is_on_sale`, `is_delete`, `addtime`, `logo`, `sm_logo`, `mid_logo`, `big_logo`, `mbig_logo`, `brand_id`) VALUES
(2, '新的联想商品', '123.00', '321.00', '', '是', '否', '2015-10-15 14:48:03', '', '', '', '', '', 0),
(3, '测试相册', '111.00', '222.00', '', '是', '否', '2015-10-15 16:05:05', '', '', '', '', '', 0);


-- --------------------------------------------------------

--
-- 表的结构 `p2018_goods_attr`
--

CREATE TABLE IF NOT EXISTS `p2018_goods_attr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_id` mediumint(8) unsigned NOT NULL COMMENT '属性Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品属性' AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `p2018_goods_attr`
--

INSERT INTO `p2018_goods_attr` (`id`, `attr_value`, `attr_id`, `goods_id`) VALUES
(1, '白色', 1, 7),
(2, '黑色', 1, 7),
(3, '绿色', 1, 7),
(4, '2015-10-01', 4, 7),
(5, 'ios', 5, 7),
(6, 'android', 5, 7),
(7, '富士白', 1, 8),
(10, '2015-10-01', 4, 8),
(11, 'ios', 5, 8),
(12, 'windows', 5, 8),
(13, '14寸', 10, 8),
(15, '蓝色', 1, 8),
(16, 'android', 5, 8),
(18, '金色', 1, 8),
(19, '富士白', 1, 8);

-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- 表的结构 `p2018_goods_number`
--

CREATE TABLE IF NOT EXISTS `p2018_goods_number` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `goods_number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存量',
  `goods_attr_id` varchar(150) NOT NULL COMMENT '商品属性表的ID,如果有多个，就用程序拼成字符串存到这个字段中',
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='库存量';

-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- 表的结构 `p2018_goods_pic`
--

CREATE TABLE IF NOT EXISTS `p2018_goods_pic` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `pic` varchar(150) NOT NULL COMMENT '原图',
  `sm_pic` varchar(150) NOT NULL COMMENT '小图',
  `mid_pic` varchar(150) NOT NULL COMMENT '中图',
  `big_pic` varchar(150) NOT NULL COMMENT '大图',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品相册' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `p2018_goods_pic`
--

INSERT INTO `p2018_goods_pic` (`id`, `pic`, `sm_pic`, `mid_pic`, `big_pic`, `goods_id`) VALUES
(2, 'Goods/2015-10-15/561f5e4374c7d.jpg', 'Goods/2015-10-15/thumb_2_561f5e4374c7d.jpg', 'Goods/2015-10-15/thumb_1_561f5e4374c7d.jpg', 'Goods/2015-10-15/thumb_0_561f5e4374c7d.jpg', 3),
(4, 'Goods/2015-10-15/561f6f5e19948.jpg', 'Goods/2015-10-15/thumb_2_561f6f5e19948.jpg', 'Goods/2015-10-15/thumb_1_561f6f5e19948.jpg', 'Goods/2015-10-15/thumb_0_561f6f5e19948.jpg', 3),
(5, 'Goods/2015-10-15/561f6f6018a1a.jpg', 'Goods/2015-10-15/thumb_2_561f6f6018a1a.jpg', 'Goods/2015-10-15/thumb_1_561f6f6018a1a.jpg', 'Goods/2015-10-15/thumb_0_561f6f6018a1a.jpg', 3),
(6, 'Goods/2015-10-15/561f6f612ab67.jpg', 'Goods/2015-10-15/thumb_2_561f6f612ab67.jpg', 'Goods/2015-10-15/thumb_1_561f6f612ab67.jpg', 'Goods/2015-10-15/thumb_0_561f6f612ab67.jpg', 3),
(7, 'Goods/2015-10-15/561f6f7151c1c.gif', 'Goods/2015-10-15/thumb_2_561f6f7151c1c.gif', 'Goods/2015-10-15/thumb_1_561f6f7151c1c.gif', 'Goods/2015-10-15/thumb_0_561f6f7151c1c.gif', 3);

-- --------------------------------------------------------

--
-- 表的结构 `p2018_member_level`
--

CREATE TABLE IF NOT EXISTS `p2018_member_level` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `jifen_bottom` mediumint(8) unsigned NOT NULL COMMENT '积分下限',
  `jifen_top` mediumint(8) unsigned NOT NULL COMMENT '积分上限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员级别' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `p2018_member_level`
--

INSERT INTO `p2018_member_level` (`id`, `level_name`, `jifen_bottom`, `jifen_top`) VALUES
(1, '注册会员', 0, 5000),
(2, '初级会员', 5001, 10000),
(3, '高级会员', 10001, 20000),
(4, 'VIP', 20001, 16777215);

-- --------------------------------------------------------

--
-- 表的结构 `p2018_member_price`
--

CREATE TABLE IF NOT EXISTS `p2018_member_price` (
  `price` decimal(10,2) NOT NULL COMMENT '会员价格',
  `level_id` mediumint(8) unsigned NOT NULL COMMENT '级别Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  KEY `level_id` (`level_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员价格';

--
-- 转存表中的数据 `p2018_member_price`
--

INSERT INTO `p2018_member_price` (`price`, `level_id`, `goods_id`) VALUES
('333.00', 2, 2),
('444.00', 4, 2);

truncate TABLE p2018_category;
INSERT INTO `p2018_category` (`id`, `cat_name`, `parent_id`) VALUES
(1, '家用电器', 0),
(2, '手机、数码、京东通信', 0),
(3, '电脑、办公', 0),
(4, '家居、家具、家装、厨具', 0),
(5, '男装、女装、内衣、珠宝', 0),
(6, '个护化妆', 0),
(21, 'iphone', 2),
(8, '运动户外', 0),
(9, '汽车、汽车用品', 0),
(10, '母婴、玩具乐器', 0),
(11, '食品、酒类、生鲜、特产', 0),
(12, '营养保健', 0),
(13, '图书、音像、电子书', 0),
(14, '彩票、旅行、充值、票务', 0),
(15, '理财、众筹、白条、保险', 0),
(16, '大家电', 1),
(17, '生活电器', 1),
(18, '厨房电器', 1),
(19, '个护健康', 1),
(20, '五金家装', 1),
(22, '冰箱', 16);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- 表的结构 `p2018_type`
--

CREATE TABLE IF NOT EXISTS `p2018_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='类型' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `p2018_type`
--

INSERT INTO `p2018_type` (`id`, `type_name`) VALUES
(1, '手机'),
(2, '服装'),
(3, '书');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT *8/;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
 */
 -- --------------------------------------------------------

--
-- 表的结构 `p2018_attribute`
--

CREATE TABLE IF NOT EXISTS `p2018_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` enum('唯一','可选') NOT NULL COMMENT '属性类型',
  `attr_option_values` varchar(300) NOT NULL DEFAULT '' COMMENT '属性可选值',
  `type_id` mediumint(8) unsigned NOT NULL COMMENT '所属类型Id',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='属性表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `p2018_attribute`
--

INSERT INTO `p2018_attribute` (`id`, `attr_name`, `attr_type`, `attr_option_values`, `type_id`) VALUES
(1, '颜色', '可选', '白色,黑色,绿色,紫色,蓝色,金色,银色,粉色,富士白', 1),
(3, '出版社', '唯一', '人民大学出版社,清华大学出版社,工业大学出版社', 3),
(4, '出厂日期', '唯一', '', 1),
(5, '操作系统', '可选', 'ios,android,windows', 1),
(6, '页数', '唯一', '', 3),
(7, '作者', '唯一', '', 3),
(8, '材质', '唯一', '', 2),
(9, '尺码', '可选', 'M,XL,XXL,XXXL,XXXXL', 2),
(10, '屏幕尺寸', '唯一', '', 1);

-- --------------------------------------------------------
ALTER TABLE  `p2018_goods` ADD  `type_id` MEDIUMINT UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '类型Id'



--
-- 表的结构 `p2018_privilege`
--

CREATE TABLE IF NOT EXISTS `p2018_privilege` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='权限';

--
-- 转存表中的数据 `p2018_privilege`
--

INSERT INTO `p2018_privilege` (`id`, `pri_name`, `module_name`, `controller_name`, `action_name`, `parent_id`) VALUES
(1, '商品模块', '', '', '', 0),
(2, '商品列表', 'Admin', 'Goods', 'lst', 1),
(3, '添加商品', 'Admin', 'Goods', 'add', 2),
(4, '修改商品', 'Admin', 'Goods', 'edit', 2),
(5, '删除商品', 'Admin', 'Goods', 'delete', 2),
(6, '分类列表', 'Admin', 'Category', 'lst', 1),
(7, '添加分类', 'Admin', 'Category', 'add', 6),
(8, '修改分类', 'Admin', 'Category', 'edit', 6),
(9, '删除分类', 'Admin', 'Category', 'delete', 6),
(10, 'RBAC', '', '', '', 0),
(11, '权限列表', 'Admin', 'Privilege', 'lst', 10),
(12, '添加权限', 'Privilege', 'Admin', 'add', 11),
(13, '修改权限', 'Admin', 'Privilege', 'edit', 11),
(14, '删除权限', 'Admin', 'Privilege', 'delete', 11),
(15, '角色列表', 'Admin', 'Role', 'lst', 10),
(16, '添加角色', 'Admin', 'Role', 'add', 15),
(17, '修改角色', 'Admin', 'Role', 'edit', 15),
(18, '删除角色', 'Admin', 'Role', 'delete', 15),
(19, '管理员列表', 'Admin', 'Admin', 'lst', 10),
(20, '添加管理员', 'Admin', 'Admin', 'add', 19),
(21, '修改管理员', 'Admin', 'Admin', 'edit', 19),
(22, '删除管理员', 'Admin', 'Admin', 'delete', 19),
(23, '类型列表', 'Admin', 'Type', 'lst', 1),
(24, '添加类型', 'Admin', 'Type', 'add', 23),
(25, '修改类型', 'Admin', 'Type', 'edit', 23),
(26, '删除类型', 'Admin', 'Type', 'delete', 23),
(27, '属性列表', 'Admin', 'Attribute', 'lst', 23),
(28, '添加属性', 'Admin', 'Attribute', 'add', 27),
(29, '修改属性', 'Admin', 'Attribute', 'edit', 27),
(30, '删除属性', 'Admin', 'Attribute', 'delete', 27),
(31, 'ajax删除商品属性', 'Admin', 'Goods', 'ajaxDelGoodsAttr', 4),
(32, 'ajax删除商品相册图片', 'Admin', 'Goods', 'ajaxDelImage', 4),
(33, '会员管理', '', '', '', 0),
(34, '会员级别列表', 'Admin', 'MemberLevel', 'lst', 33),
(35, '添加会员级别', 'Admin', 'MemberLevel', 'add', 34),
(36, '修改会员级别', 'Admin', 'MemberLevel', 'edit', 34),
(37, '删除会员级别', 'Admin', 'MemberLevel', 'delete', 34),
(38, '品牌列表', 'Admin', 'Brand', 'lst', 1);

-- --------------------------------------------------------

--
-- 表的结构 `p2018_role`
--

CREATE TABLE IF NOT EXISTS `p2018_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='角色';

--
-- 转存表中的数据 `p2018_role`
--

INSERT INTO `p2018_role` (`id`, `role_name`) VALUES
(1, '商品模块管理员'),
(2, 'RBAC管理员');

-- --------------------------------------------------------

--
-- 表的结构 `p2018_role_pri`
--

CREATE TABLE IF NOT EXISTS `p2018_role_pri` (
  `pri_id` mediumint(8) unsigned NOT NULL COMMENT '权限id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  KEY `pri_id` (`pri_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限';

--
-- 转存表中的数据 `p2018_role_pri`
--

INSERT INTO `p2018_role_pri` (`pri_id`, `role_id`) VALUES
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(31, 1),
(32, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(38, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1);

-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- 表的结构 `p2018_admin`
--

CREATE TABLE IF NOT EXISTS `p2018_admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员';

--
-- 转存表中的数据 `p2018_admin`
--

INSERT INTO `p2018_admin` (`id`, `username`, `password`) VALUES
(1, 'root', '21232f297a57a5a743894a0e4a801fc3'),
(3, 'goods', '59da8bd04473ac6711d74cd91dbe903d'),
(4, 'rbac', 'eae22f4f89a3e1a049b3992d107229d1');

-- --------------------------------------------------------

--
-- 表的结构 `p2018_admin_role`
--

CREATE TABLE IF NOT EXISTS `p2018_admin_role` (
  `admin_id` mediumint(8) unsigned NOT NULL COMMENT '管理员id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色';

--
-- 转存表中的数据 `p2018_admin_role`
--

INSERT INTO `p2018_admin_role` (`admin_id`, `role_id`) VALUES
(3, 1),
(4, 2);

-- --------------------------------------------------------

ALTER TABLE p2018_goods ADD `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格';
ALTER TABLE p2018_goods ADD `promote_start_date` datetime NOT NULL COMMENT '促销开始时间';
ALTER TABLE p2018_goods ADD `promote_end_date` datetime NOT NULL COMMENT '促销结束时间';
ALTER TABLE p2018_goods ADD `is_new` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否新品';
ALTER TABLE p2018_goods ADD `is_hot` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否热卖';
ALTER TABLE p2018_goods ADD `is_best` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否精品';

ALTER TABLE p2018_goods ADD `sort_num` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序的数字';
ALTER TABLE p2018_goods ADD `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐楼层',
ALTER TABLE p2018_category ADD `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐楼层',
___________________________________________________________________
