@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/account.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
<div class="bg_container">
    <main style="min-height: 536px;">
        <nav class="usercenter-row wash-code deposit-records" style="border:1px solid #ddd;border-top: 0;margin-top: 25px;margin-bottom: 16px;background-color: #fff;min-height: 527px;">
          <div class="account-top" style="width: 100%;"><b>站内短信</b></div>
            <main>
                <table class="table table-bordered tab-checkbox SMS-table" style="margin-left: 30px;">
                    <thead>
	                    <tr>
	                        <th></th>
	                        <th>主题</th>
	                        <th>时间</th>
	                        <th>状态</th>
	                    </tr>
                    </thead>
                    <tbody>
	                    <tr>
	                        <td><input type="checkbox" style="margin-top: 0;"/></td>
	                        <td style="cursor: pointer;" >主题主题主题主题主题主题主题主题主题主题主题主题</td>
	                        <td>2017-02-16 16:01:47</td>
	                        <td>已读</td>
	                    </tr>
	                    <tr>
	                        <td><input type="checkbox" style="margin-top: 0;"/></td>
	                        <td style="cursor: pointer;" >主题主题主题主题主题主题主题主题主题主题主题主题</td>
	                        <td>2017-02-16 16:01:47</td>
	                        <td>已读</td>
	                    </tr>
	                    <tr>
	                        <td><input type="checkbox" style="margin-top: 0;"/></td>
	                        <td style="cursor: pointer;" class="SMS-some">主题主题主题主题主题主题主题主题主题主题主题主题</td>
	                        <td>2017-02-16 16:01:47</td>
	                        <td style="color: red;font-weight: bold;">未读</td>
	                    </tr>
                    </tbody>
                </table>
            </main>
            <div style="width: 100%;">
                <div class="pull-left" style="margin-left:30px;margin-top: 15px;">
                        <span class="btn btn-blue all-SMS" style="margin-left: 0;border: 0;border-radius: 2px;">全选</span>
                        <span class="btn btn-blue clear-SMS" style="border: 0;border-radius: 2px;">删除</span>
                </div>
                <nav class="pull-right" style="margin-right:35px;margin-top: 15px;">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>
    </main>

    <div id="SMS" style="display: none;">
        <div>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias aperiam blanditiis dolore mollitia, neque
                obcaecati provident tempora? Consectetur enim est, iure iusto labore laudantium maiores quibusdam repellat,
                repellendus saepe, voluptatem.
            </div>
            <div>Accusamus dolor eaque error hic id libero odit officia reprehenderit sint, veritatis. Alias amet dolore
                eius expedita nulla officia, omnis, perspiciatis porro quasi quibusdam recusandae reiciendis sed voluptas,
                voluptatibus voluptatum?
            </div>
            <div>Architecto culpa doloremque dolores dolorum enim eveniet facilis impedit inventore ipsum iste magnam nam
                nisi numquam praesentium quae quaerat quasi, qui quidem quo quod quos, repudiandae veniam voluptates!
                Deleniti, ipsum?
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{!! asset('./app/js/Customer-Service.js') !!}"></script>
@endsection

