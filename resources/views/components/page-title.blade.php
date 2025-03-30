<div class="flex justify-between bg-secondary">


    <div class=" text-primary-dark font-bold text-2xl ps-5 pb-2">{{$title}}
    </div>
    @isset($previus)
        <a href="{{$previus}}" class="py-2 px-3 text-white rounded-sm hover:text-primary"
           aria-current="page">Voltar</a>
    @endisset
</div>
