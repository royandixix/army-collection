export function VacationCard({ img, imgAlt, eyebrow, title, pricing, url }) {
    return (
      <div>
        <img className="rounded-lg" src={img} alt={imgAlt} />
        <div className="mt-4">
          <div className="text-xs font-bold text-sky-500">
            {eyebrow}
          </div>
          <div className="mt-1 font-bold text-gray-700">
            <a href={url} className="hover:underline">
              {title}
            </a>
          </div>
          <div className="mt-2 text-sm text-gray-600 checked@section('')
              
          @show">
            <i className="ri-price-tag-2-line"></i>
            {' '}
            <span className="font-semibold">{pricing}</span>
          </div>
          <div className="mt-1 text-sm text-gray-500">  
            {pricing}
          </div>
        </div>
      </div>
    );
  }
  