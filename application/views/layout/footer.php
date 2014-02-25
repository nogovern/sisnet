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
    });
    </script>
  </body>
</html>