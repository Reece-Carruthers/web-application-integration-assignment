import UserContext from "../../helpers/userContext.jsx";
import {useContext} from "react";

/**
 * A component to display information about conference content
 * @param id
 * @param title
 * @param abstract
 * @param type
 * @param award
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 */
export default function ContentContainer({title, abstract, type, award}) {

    const awardExists = award !== null

    return (
        <div className="text-justify">
            <div className="flex justify-between items-center border-b-2 border-orange-300 mt-2 mb-2">
                <p className="text-lg 2xl:text-2xl font-medium text-gray-900 italic">Title</p>
            </div>
            <h2 className="text-lg 2xl:text-2xl">{title}</h2>
            {abstract && (
                <>
                    <h3 className="text-lg 2xl:text-2xl text-gray-800 italic font-normal mb-4 border-b-2 border-orange-200 mt-2">Abstract</h3>
                    <p className="2xl:text-xl">{abstract}</p>
                </>
            )}
            <h3 className="text-lg 2xl:text-2xl text-gray-800 italic font-normal mb-4 border-b-2 border-orange-200 mt-2">Type of research</h3>
            <p className="2xl:text-xl mb-2">{type}</p>
            <div>
                {awardExists && (
                    <>
                        <h3 className="text-lg 2xl:text-2xl border-b-2 border-orange-200 mt-2 mb-2">Award</h3>
                        <p className="2xl:text-xl mb-4">{award}</p>
                    </>
                )}
            </div>
        </div>
    )
}