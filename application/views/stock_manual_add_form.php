    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <?php
            // 에러 출력
            echo validation_errors();
          ?>
 
          <div>
            <?php echo form_open('', 'role="form" class="form-horizontal"');?>
            <!-- <form role="form"> -->
              <h2>재고 등록 양식 (관리자 전용)</h2>

              <div class="form-group">
                <label for="category" class="control-label">장비 종류</label>
                  <select id="category" name="category" class="form-control" disabled>
                    <option value="">-- 장비종류를 선택하세요--</option>
                    <option value="1">POS스캐너</option>
                    <option value="2">고정스캐너</option>
                    <option value="3">모니터_CRT</option>
                    <option value="4">모니터_LCD</option>
                    <option value="5">서버</option>
                    <option value="6">프린터_점포용</option>
                  </select>
              </div>

              <div class="form-group">
                <label for="part_id" class="control-label">장비를 선택하세요</label>
<?php
  echo $select_part;
?>
              </div>

              <div class="form-group">
                <label for="office_id" class="control-label">재고 사무소 선택</label>
<?php
  echo $select_office;
?>
              </div>

              <!-- 수량 등록 -->
              <div class="panel panel-default row">
                <div class="panel-heading">장비 수량 입력</div>
                <div class="panel-body">
                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="qty_minimum">
                        기준수량
                      </label>
                      <div class="col-sm-3">
                        <input type="text" placeholder="0" id="qty_minimum" name="qty_minimum" class="form-control">
                      </div>
                      <span class="help-inline col-sm-7"> <i class="fa fa-info-circle"></i> 수량을 입력하세요 </span>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="qty_new">
                        신 품
                      </label>
                      <div class="col-sm-3">
                        <input type="text" placeholder="0" id="qty_new" name="qty_new" class="form-control">
                      </div>
                      <span class="help-inline col-sm-7"> <i class="fa fa-info-circle"></i> 수량을 입력하세요 </span>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="qty_used">
                        중 고
                      </label>
                      <div class="col-sm-3">
                        <input type="text" placeholder="0" id="qty_used" name="qty_used" class="form-control">
                      </div>
                      <span class="help-inline col-sm-7"> <i class="fa fa-info-circle"></i> 수량을 입력하세요 </span>
                  </div>

                </div> <!-- end of panel-body -->
              </div>
              
              <p class="row well">
                <button class="btn btn-primary" type="submit">입력완료</button>
              </p>
            </form>
          </div>

        </div>
      </div>
    <!-- start of div.container -->
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
      $("#re_password").parent().addClass('has-warning');
      $("form").submit(function(){
        var selected = $("#part_id").val();
        if( selected == '' ){
          alert('장비를 선택하세요');
          return false;
        }

      });

    });
    </script>