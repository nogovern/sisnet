    <!--
      <hr>
      <footer>
        <p>&copy; Sisnet service, 2013</p>
      </footer>
  	-->

	 <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
    $(document).ready(function(){
      var current = '<?=(isset($current)) ? $current : "page-admin"?>';
      var $current = $("#" + current);

      $current.addClass('active');

      ///////////
      // 검색필터 - 폰트 사이즈 조정 - bs3 는 폰트사이즈 global 로 지정되어 컴파일되어 있어 이 방법 사용함
      $("#filter-form select").css('font-size', '12px');
    });
    </script>
  </body>
</html>