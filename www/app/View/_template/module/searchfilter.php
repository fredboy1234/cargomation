<style>
   .btn_round {
   width: 35px;
   height: 35px;
   display: inline-block;
   border-radius: 50%;
   text-align: center;
   line-height: 35px;
   margin-left: 10px;
   border: 1px solid #ccc;
   cursor: pointer;
   }
   .btn_round:hover {
   color: #fff;
   background: #6b4acc;
   border: 1px solid #6b4acc;
   }
   .btn_content_outer {
   display: inline-block;
   width: 85%;
   }
   .close_c_btn {
   width: 30px;
   height: 30px;
   position: absolute;
   right: 10px;
   top: 0px;
   line-height: 30px;
   border-radius: 50%;
   background: #ededed;
   border: 1px solid #ccc;
   color: #ff5c5c;
   text-align: center;
   cursor: pointer;
   }
   .add_icon {
   padding: 10px;
   border: 1px dashed #aaa;
   display: inline-block;
   border-radius: 50%;
   margin-right: 10px;
   }
   .add_group_btn {
   display: flex;
   }
   .add_group_btn i {
   font-size: 32px;
   display: inline-block;
   margin-right: 10px;
   }
   .add_group_btn span {
   margin-top: 8px;
   }
   .add_group_btn,
   .clone_sub_task {
   cursor: pointer;
   }
   .sub_task_append_area .custom_square {
   cursor: move;
   }
   .del_btn_d {
   display: inline-block;
   position: absolute;
   right: 20px;
   border: 2px solid #ccc;
   border-radius: 50%;
   width: 40px;
   height: 40px;
   line-height: 40px;
   text-align: center;
   font-size: 18px;
   }
   #vert-tabs .nav-tabs,
   #vert-tabs .nav-link{
   border: none !important;
   }
   #row_head, .box1 label, .box2 label {
      color:#3778be !important;
      font-size: 13pt;
   }
</style>
<div class="row">
   <div class="col-md-8 form_sec_outer_task ">
      <div id ="row_head" class="row">
         <div class="col-md-4">
            <label>Search</label>
         </div>
         <div class="col-md-3">
            <label>Type</label>
         </div>
         <div class="col-md-5">
            <label>Value</label>
         </div>
      </div>
      <div class="col-md-12 p-0">
         <div class="col-md-12 form_field_outer p-0">
            <div class="row form_field_outer_row 1" section="1">
               <div class="form-group col-md-4">
                  <select name="search[]" id="no_search_1" class="form-control search-list" value="">
                     <option>--Select type--</option>
                  </select>
               </div>
               <div class="form-group col-md-3">
                  <select name="type[]" id="no_type_1" class="form-control">
                     <option>--Select type--</option>
                  </select>
               </div>
               <div class="form-group col-md-5">
                  <input  name="value[]" id="no_value_1" type="text" class="form-control w_90" placeholder="Enter search value">
               </div>
               <div class="form-group col-md-2">
                  <select name="cond[]" id="no_cond_1" class="form-control exclude" disabled>
                     <option value="OR">OR</option>
                     <option value="AND">AND</option>
                  </select>
               </div>
               <div class="form-group col-md-10 add_del_btn_outer">
                  <button class="btn_round add_node_btn_frm_field" title="Copy or clone this row" section="1">
                  <i class="fas fa-copy"></i>
                  </button>
                  <button class="btn_round remove_node_btn_frm_field" disabled="">
                  <i class="fas fa-trash-alt"></i>
                  </button>
               </div>
            </div>
         </div>
         <div class="col-md-12 p-0">
            <div class="row">
               <div class="form-group col-md-4">
                  <select name="" id="add_filters" class="form-control search-list add_new_frm_field_btn">
                     <option>--Select search type--</option>
                     <option value="shipment_id">--Add search type--</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="col-md-12 p-0">
            <button type="button" id="savefilter" class="btn btn-warning"><i class="fas fa-save"></i> Save Search </button>
            <button type="button" id="clearFilter" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Clear Filter </button>
            <button type="submit" id="searchFilter" class="btn btn-primary float-right"><i class="fas fa-search"></i> Search Data </button>
         </div>

      </div>
   </div>
   <div class="col-sm-4 form_sec_outer_task ">
      <div id ="row_head" class="row">
         <div class="col-md-4">
            <label>Recent Search</label>
         </div>
      </div>
      <div class="col-md-12 p-0">
         <div class="col-md-12 p-0">
            <div class="row">
               <div class="form-group col-md-12">
                  <select id="recent_search" multiple="" class="form-control" size="6"></select>
               </div>
            </div>
         </div>
         <div class="col-md-12 mt-2 text-left">
            <button type="button" id="loadRecent" class="btn btn-success">
               <i class="fas fa-search"></i> Load Recent Search
            </button>
         </div>
      </div>
   </div>
</div>

