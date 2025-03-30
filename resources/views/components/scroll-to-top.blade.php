<button id="btnTop"
        class="text-white fixed end-6 bottom-[5rem] group bg-secondary p-3 rounded-full shadow-lg hover:bg-primary transition duration-300 ease-in-out hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-up">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M12 5l0 14"/>
        <path d="M18 11l-6 -6"/>
        <path d="M6 11l6 -6"/>
    </svg>
</button>

<script>
    var btnTopEl = document.getElementById("btnTop");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 200) {
            btnTopEl.classList.remove("hidden");
        } else {
            btnTopEl.classList.add("hidden");
        }
    });

    btnTopEl.addEventListener("click", () => {
        window.scrollTo({top: 0, behavior: "smooth"});
    });
</script>
