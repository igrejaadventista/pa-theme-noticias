class CardAuthor extends window.Slim {
  constructor() {
    super();

    if (!this.author) return;

    this.parseAvatar();
  }

  parseAvatar() {
    if (!this.author.avatar || !this.author.avatar.hasOwnProperty("full"))
      return;

    this.author.avatar = this.author.avatar.full;
  }
}

CardAuthor.useShadow = false;
CardAuthor.template = /*html*/ `
    <div class="pa-author-item pa-blog-item">
        <a href="{{ this.author.link }}" title="{{ this.author.name }}">
            <div class="row align-items-center">
                <div class="col-auto pe-3">
                    <div class="ratio ratio-1x1">
                        <figure class="figure m-0">
                            <img src="{{ this.author.avatar ? this.author.avatar : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkwMCIgdmlld0JveD0iMCAwIDE2MDAgOTAwIj4KICA8cmVjdCBpZD0iUmV0w6JuZ3Vsb18xIiBkYXRhLW5hbWU9IlJldMOibmd1bG8gMSIgd2lkdGg9IjE2MDAiIGhlaWdodD0iOTAwIiBmaWxsPSIjOTA5MDkwIi8+Cjwvc3ZnPg==' }}" class="figure-img rounded-circle m-0 h-100 w-100" alt="{{ this.author.name }}" />
                        </figure>	
                    </div>
                </div>

                <div class="col pe-sm-0 ps-0 ps-sm-3">
                    <div class="card-body p-0">
                        <h3 class="fw-bold h5">{{ this.author.name }}</h3>

                        <span *if="{{ this.author.column.name }}" class="h5 mb-2 pa-truncate-3">{{ this.author.column.name }}</span>

                        <p *if="{{ this.author.column.excerpt }}" class="m-0 pa-truncate-3">{{ this.author.column.excerpt }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
`;

customElements.define("card-author", CardAuthor);
