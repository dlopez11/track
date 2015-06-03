{% for item in smartMenu.get() %}
    <li role="presentation" class="{{item.class}}">
        <a href="{{ url(item.url) }}">{{item.title}}</a>
    </li>
{% endfor %}