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

require_once CLASS_REALDIR . 'SC_Product.php';

class SC_Product_Ex extends SC_Product
{
     /**
     * SC_Queryインスタンスに設定された検索条件を元に並び替え済みの検索結果商品IDの配列を取得する。
     *
     * 検索条件は, SC_Query::setWhere() 関数で設定しておく必要があります.
     *
     * @param SC_Query $objQuery SC_Query インスタンス
     * @param array $arrVal 検索パラメーターの配列
     * @return array 商品IDの配列
     */

    public function findProductIdsOrder(&$objQuery, $arrVal = array())
    {
        $table = <<< __EOS__
           dtb_products AS alldtl JOIN dtb_products_class AS P
                    ON  P.product_id = alldtl.product_id
                    LEFT JOIN dtb_class AS A
                    ON P.classcategory_id1 = A.class_id
                    LEFT JOIN dtb_class AS B
                    ON P.classcategory_id2 = B.class_id
__EOS__;
        $objQuery->setGroupBy('alldtl.product_id');
        if (is_array($this->arrOrderData) and $objQuery->order == '') {
            $o_col = $this->arrOrderData['col'];
            $o_table = $this->arrOrderData['table'];
            $o_order = $this->arrOrderData['order'];
            $order = <<< __EOS__
                    (
                        SELECT $o_col
                        FROM
                            $o_table as T2
                        WHERE T2.product_id = alldtl.product_id
                        ORDER BY T2.$o_col $o_order
                        LIMIT 1
                    ) $o_order, product_id
__EOS__;
            $objQuery->setOrder($order);
        }

        $results = $objQuery->select('alldtl.product_id', $table, '', $arrVal, MDB2_FETCHMODE_ORDERED);
        $resultValues = array();
        foreach ($results as $val) {
            $resultValues[] = $val[0];
        }
        return $resultValues;
    }

    /**
     * SC_Queryインスタンスに設定された検索条件をもとに対象商品数を取得する.
     *
     * 検索条件は, SC_Query::setWhere() 関数で設定しておく必要があります.
     *
     * @param SC_Query $objQuery SC_Query インスタンス
     * @param array $arrVal 検索パラメーターの配列
     * @return array 対象商品ID数
     */
    public function findProductCount(&$objQuery, $arrVal = array())
    {
        $table = <<< __EOS__
           dtb_products AS alldtl JOIN dtb_products_class AS P
                    ON  P.product_id = alldtl.product_id
                    LEFT JOIN dtb_class AS A
                    ON P.classcategory_id1 = A.class_id
                    LEFT JOIN dtb_class AS B
                    ON P.classcategory_id2 = B.class_id
__EOS__;
        $objQuery->setGroupBy('P.product_id, A.name, B.name');

        return $objQuery->get('COUNT(DISTINCT(alldtl.product_id))', $table, '', $arrVal);
    }

}
