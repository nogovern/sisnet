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
    });
    </script>
  </body>
</html>