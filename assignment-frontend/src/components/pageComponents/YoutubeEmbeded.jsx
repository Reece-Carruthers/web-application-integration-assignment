/**
 * Takes a youtube URL and creates an embedded video
 * @param title
 * @param youtubeURL
 * @returns {JSX.Element}
 * @author https://dev.to/bravemaster619/simplest-way-to-embed-a-youtube-video-in-your-react-app-3bk2
 */
export default function YoutubeEmbed({title, youtubeURL}) {

    const embedId = youtubeURL.split('v=')[1].split('&')[0]

    return (
        <>
                <iframe className="aspect-video w-full md:w-3/4 xl:w-1/2"
                    src={`https://www.youtube.com/embed/${embedId}`}
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                    title={title}
                />
        </>
    )
}