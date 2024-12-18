<section id="search-modal">
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" id="search-input" class="form-control bg-transparent p-3"
                            placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
                <div class="modal-body">
                    <ul id="search-results" class="list-group w-75 mx-auto"></ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- testing pencarian pakai data sementara (menu 404 dan routes) --}}
<script>
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const results = document.getElementById('search-results');
        results.innerHTML = '';

        const data = [{
                name: 'Coffee',
                route: '/menu/coffee'
            },
            {
                name: 'Mocktail',
                route: '/menu/mocktail'
            },
            {
                name: 'Food',
                route: '/menu/food'
            },
            {
                name: 'About Us',
                route: '/about'
            },
            {
                name: 'Contact',
                route: '/contact'
            },
            {
                name: 'Blog',
                route: '/map'
            },
            {
                name: 'Home',
                route: '/'
            }
        ];

        const filteredData = data.filter(item => item.name.toLowerCase().includes(query));

        filteredData.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerHTML = `<a href="${item.route}">${item.name}</a>`;
            results.appendChild(li);
        });
    });
</script>
