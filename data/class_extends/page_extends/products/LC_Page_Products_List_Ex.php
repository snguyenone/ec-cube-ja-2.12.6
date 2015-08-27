<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2012 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_List.php';

/**
 * LC_Page_Products_List のページクラス(拡張).
 *
 * LC_Page_Products_List をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Products_List_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Products_List_Ex extends LC_Page_Products_List
{

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }

    /**
     * 検索条件のwhere文とかを取得
     *
     * @return array
     */
    public function lfGetSearchCondition($arrSearchData)
    {
        $searchCondition = array(
            'where' => '',
            'arrval' => array(),
            'where_category' => '',
            'arrvalCategory' => array()
        );

        // カテゴリからのWHERE文字列取得
        if ($arrSearchData['category_id'] != 0) {
            list($searchCondition['where_category'], $searchCondition['arrvalCategory']) = SC_Helper_DB_Ex::sfGetCatWhere($arrSearchData['category_id']);
        }
        // ▼対象商品IDの抽出
        // 商品検索条件の作成（未削除、表示）
        $searchCondition['where'] = 'alldtl.del_flg = 0 AND alldtl.status = 1 ';

        if (strlen($searchCondition['where_category']) >= 1) {
            $searchCondition['where'] .= ' AND EXISTS (SELECT * FROM dtb_product_categories WHERE ' . $searchCondition['where_category'] . ' AND product_id = alldtl.product_id)';
            $searchCondition['arrval'] = array_merge($searchCondition['arrval'], $searchCondition['arrvalCategory']);
        }

        // 商品名をwhere文に
        $name = $arrSearchData['name'];
        $name = str_replace(',', '', $name);
        // 全角スペースを半角スペースに変換
        $name = str_replace('　', ' ', $name);
        // スペースでキーワードを分割
        $names = preg_split('/ +/', $name);
        // 分割したキーワードを一つずつwhere文に追加
        foreach ($names as $val) {
            if (strlen($val) > 0) {
                $searchCondition['where'] .= ' AND ( alldtl.name ILIKE ? OR alldtl.comment3 ILIKE ? OR A.name ILIKE ? OR B.name ILIKE ?) ';
                $searchCondition['arrval'][] = "%$val%";
                $searchCondition['arrval'][] = "%$val%";
                $searchCondition['arrval'][] = "%$val%";
                $searchCondition['arrval'][] = "%$val%";
            }
        }

        // メーカーらのWHERE文字列取得
        if ($arrSearchData['maker_id']) {
            $searchCondition['where'] .= ' AND alldtl.maker_id = ? ';
            $searchCondition['arrval'][] = $arrSearchData['maker_id'];
        }

        $searchCondition['where_for_count'] = $searchCondition['where'];

        // 在庫無し商品の非表示
        if (NOSTOCK_HIDDEN) {
            $searchCondition['where'] .= ' AND EXISTS(SELECT * FROM dtb_products_class WHERE product_id = alldtl.product_id AND del_flg = 0 AND (stock >= 1 OR stock_unlimited = 1))';
            $searchCondition['where_for_count'] .= ' AND EXISTS(SELECT * FROM dtb_products_class WHERE product_id = alldtl.product_id AND del_flg = 0 AND (stock >= 1 OR stock_unlimited = 1))';
        }
        return $searchCondition;
    }

}
