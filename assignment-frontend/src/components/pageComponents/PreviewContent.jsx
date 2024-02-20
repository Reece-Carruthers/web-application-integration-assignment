/**
 * A component to display a preview of content
 * @param id
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 */
export default function PreviewContent({abstract}) {

    return (
        <>
            <div className="flex justify-center">
                <div className="p-8 m-2 bg-neutral-100 text-wrap text-justify text-base text-gray-600 rounded-xl shadow lg:w-1/2">
                    <p className="sm:text-lg md:text-xl lg:text-2xl 2xl:text-4xl text-gray-700 italic font-normal mb-1 lg:p-2">Abstract</p>
                    <p className="border-b-2 border-orange-300 mt-2 mb-2"></p>
                    <p className="sm:text-base md:text-lg lg:text-xl 2xl:text-2xl text-gray-700 italic font-normal mb-1 overflow-auto lg:p-5">{abstract}</p>
                </div>
            </div>

        </>
    )
}