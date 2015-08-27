<?php

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * 特定商取引に関する法律に基づく表記 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Order.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_News_Detail extends LC_Page_Ex
{

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
//        $this->tpl_mainpage = TEMPLATE_REALDIR.'news/index.tpl';
//        $this->tpl_page_category = 'news';
        $this->tpl_title = '特定商取引に関する法律に基づく表記';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }
    /**
     * Page のAction.
     *
     * @return void
     */
    function action() {
        // 会員クラス
        $objCustomer = new SC_Customer_Ex();

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $from = 'dtb_news';
        $where = 'news_id = ? and del_flg = 0';
        $arrval = array($_GET['news_id']);
        $arrNews = $objQuery->select($col, $from, $where, $arrval);
        $this->arrNews = $arrNews[0];
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
}