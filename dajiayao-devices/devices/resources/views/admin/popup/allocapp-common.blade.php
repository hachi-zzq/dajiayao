<div class="row">
    <table class="table" style="text-align: left;">
        <thead>
        <tr>
            <th>应用名称</th>
            <th>应用类型</th>
        </tr>
        </thead>
        <tbody>
        @foreach($apps as $a)
            <tr>
                <td>
                    <label>
                        <input type="radio" name="radio-apps" class="apps-list-item" value="{{$a->id}}">
                    </label>
                    {{$a->name}}
                </td>
                <td>{{$a->typeName()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>