   <main class="app-main">
       <!--begin::App Content Header-->
       <div class="app-content-header">
           <!--begin::Container-->
           <div class="container-fluid">
               <!--begin::Row-->
               <div class="row">
                   <div class="col-sm-6">
                       <h3 class="mb-0">Dashboard</h3>
                       <p>Hello <?= session()->get('user_email') . " " . session()->get('user_id'); ?> Welcome to the dashboard page.</p>
                   </div>
                   <div class="col-sm-6">

                   </div>
               </div>
               <!--end::Row-->
           </div>
           <!--end::Container-->
       </div>
       <div class="app-content">
           <!--begin::Container-->
           <div class="container-fluid">
               <!-- Info boxes -->

               <!--end::Row-->
               <!--begin::Row-->
               <div class="row">
                   <!-- Start col -->

                   <!-- /.col -->

                   <!-- /.col -->
               </div>
               <!--end::Row-->
           </div>
           <!--end::Container-->
       </div>
       <!--end::App Content-->
   </main>