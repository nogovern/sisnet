    <!-- 
    <div class="container"> 
      <hr>
      <footer>
        <p class="text-right">&copy; Sisnet service, 2014</p>
      </footer>
    </div> 
    -->

	 <!-- Le javascript
    ================================================== -->
    <script type="text/javascript">
    $(document).ready(function(){
      // 현재 페이지 메뉴 활성화
      var current = '<?=(isset($current)) ? $current : "page-admin"?>';
      $("#" + current).addClass('active');

      // 검색필터 - 폰트 사이즈 조정 - bs3 는 폰트사이즈 global 로 지정되어 컴파일되어 있어 이 방법 사용함
      $("#filter-form select").css('font-size', '12px');

      // 업무 리스트 double click 이벤트 등록
      $("#op_list tbody tr").dblclick(function(event) {
        var href = $("td .btn_view", this).data('href');
        gs2_go_page(href);
      });

      // datepicker 커서 변경
      $(".date-picker").mouseover(function(){
        $(this).css('cursor', 'default');
      });

      // 점포 정포 modal popup 이벤트
      $(".popup_store_info").click(function(){
        // html attribute 는 대소문자를 구분하지 않는다
        var store_id = $(this).data('storeid');
        openStoreInfo(store_id);
        // gs2_console(store_id);
        return false;
      });
    });

    // jquery-ui datepicker 한글 적용
    $(document).ready(function() {
      $.datepicker.regional['ko'] = {
        closeText: '닫기',
        prevText: '이전달',
        nextText: '다음달',
        currentText: '오늘',
        monthNames: ['1월','2월','3월','4월','5월','6월',
        '7월','8월','9월','10월','11월','12월'],
        monthNamesShort: ['1월','2월','3월','4월','5월','6월',
        '7월','8월','9월','10월','11월','12월'],
        dayNames: ['일','월','화','수','목','금','토'],
        dayNamesShort: ['일','월','화','수','목','금','토'],
        dayNamesMin: ['일','월','화','수','목','금','토'],
        weekHeader: 'Wk',
        dateFormat: 'yy-mm-dd',
        firstDay: 0,
        isRTL: false,
        duration:200,
        showAnim:'show',
        showMonthAfterYear: true,
        yearSuffix: ''};
      $.datepicker.setDefaults($.datepicker.regional['ko']);
    });
    </script>
  </body>
</html>