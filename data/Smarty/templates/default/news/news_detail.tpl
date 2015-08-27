<div id="news_detail">

    <h2>新着情報</h2>

    <!--▼CONTENTS-->
    <div id="undercolumn">
        <!--{if count($arrNews) > 0}-->
        <ul>
            <li>news_id: <!--{$arrNews.news_id|u}--></li>
            <li>news_date: <!--{$arrNews.news_date|h}--></li>
            <li>rank:  <!--{$arrNews.rank|u}--></li>
            <li>news_title:  <!--{$arrNews.news_title|h}--></li>
            <li>news_comment:  <!--{$arrNews.news_comment|h}--></li>
            <li>news_url: <!--{$arrNews.news_url|h}--></li>
            <li>news_select: <!--{$arrNews.news_select|h}--></li>
            <li>link_method: <!--{$arrNews.link_method|h}--></li>
            <li>creator_id: <!--{$arrNews.creator_id|u}--></li>
            <li>create_date: <!--{$arrNews.create_date|h}--></li>
            <li>update_date: <!--{$arrNews.update_date|h}--></li>
        </ul>
        <!--{else}-->
        存在しないニュースです。
        <!--{/if}-->
    </div>
    <!--▲CONTENTS-->

</div><!--news_detail end-->