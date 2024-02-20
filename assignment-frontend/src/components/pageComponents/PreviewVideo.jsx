import YoutubeEmbed from "./YoutubeEmbeded.jsx"

/**
 * A component to display a preview video
 * @param id
 * @param title
 * @param previewLink
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 */
export default function PreviewVideo({title, previewLink}) {

    return (
        <>
                <h2 className="sm:text-lg md:text-xl lg:text-2xl 2xl:text-4xl p-2 m-4 xl:p-5 xl:m-6 text-wrap text-center text-xl text-gray-800 font-medium">{title}</h2>
                <div className="flex-grow flex justify-center items-center px-4 mb-5 lg:mb-10">
                    <YoutubeEmbed youtubeURL={previewLink} title={title}/>
                </div>
        </>
    )
}