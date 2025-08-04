   <main class="app-main">
       <!--begin::App Content Header-->
       <div class="app-content-header">
           <!--begin::Container-->
           <div class="container-fluid">
               <!--begin::Row-->
               <div class="row">
                   <div class="col-sm-6">
                       <h3 class="mb-0">Permissions</h3>
                       <p></p>
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
                   <div class="col-md-12">
                       <div class="card mb-12">
                           <div class="card-header">
                               <h3 class="card-title">Permission List</h3>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body">

                               <table class="table table-bordered">
                                   <thead>
                                       <tr>
                                           <th style="width: 20px">Permission Name</th>
                                           <th style="width: 20px">Group</th>
                                           <th style="width: 20px">Slug</th>
                                           <th style="width: 40px">Description</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php if (!isset($error) && empty($error)): ?>

                                           <?php if (isset($show_all) && !empty($show_all)) : ?>
                                               <?php foreach ($show_all as $show_all): ?>
                                                   <tr class="align-middle">
                                                       <td style="width: 20%;"><?= $show_all['name']; ?></td>
                                                       <td style="width: 20%;"><?= $show_all['name']; ?></td>
                                                       <td style="width: 20%;"><?= $show_all['slug']; ?></td>
                                                       <td style="width: 40%;"><?= $show_all['description']; ?></td>
                                                   </tr>
                                               <?php endforeach; ?>
                                           <?php else: ?>

                                               <tr>
                                                   <td colspan="4" class="text-center text-muted">
                                                       <i class="bi bi-database" style="font-size: 3rem; opacity: 0.3;"></i>
                                                       <div class="mt-2 small" style="opacity: 0.5;">No data available</div>
                                                   </td>
                                               </tr>
                                           <?php
                                            endif;
                                            ?>


                                       <?php else: ?>
                                           <tr colspan="4">
                                               <td class="text-center text-danger">
                                                   <i class="bi bi-database-x" style="font-size: 3rem; opacity: 0.5;"></i>
                                                   <div class="mt-2 small" style="opacity: 0.6;">We couldn't load the information right now. Please refresh the page or check back shortly.</div>
                                               </td>
                                           </tr>

                                       <?php endif; ?>

                                   </tbody>
                               </table>
                           </div>
                           <!-- /.card-body -->
                           <div class="card-footer clearfix">
                               <ul class="pagination pagination-sm m-0 float-end">
                                   <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                   <li class="page-item"><a class="page-link" href="#">1</a></li>
                                   <li class="page-item"><a class="page-link" href="#">2</a></li>
                                   <li class="page-item"><a class="page-link" href="#">3</a></li>
                                   <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                               </ul>
                           </div>
                       </div>
                       <!-- /.card -->

                       <!-- /.card -->
                   </div>
               </div>
               <!--end::Row-->
           </div>
           <!--end::Container-->
       </div>
       <!--end::App Content-->
   </main>