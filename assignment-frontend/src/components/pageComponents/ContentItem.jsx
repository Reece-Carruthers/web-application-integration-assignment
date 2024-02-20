import ContentContainer from "./ContentContainer.jsx";
import AuthorAffiliationContainer from "./AuthorAffiliationContainer.jsx";
import Note from "./Note.jsx";
import UserContext from "../../helpers/userContext.jsx";
import {useContext} from "react";
import Message from "./Message.jsx";

/**
 * A component to display information about conference content
 * @param content
 * @param isSelected
 * @param toggleAuthorDetails
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 */
export default function ContentItem({content, isSelected, toggleAuthorDetails}) {

    const user = useContext(UserContext)

    return (
        <div
            id={content.content_id}
            className="mb-4 break-inside-avoid-column m-4 mt-8 p-8 rounded-lg bg-neutral-100 shadow-md hover:shadow-2xl active:shadow-none"
            >
            <ContentContainer
                title={content.content_title}
                abstract={content.content_abstract}
                type={content.content_type}
                award={content.award_name}
            />
            <h3 className="text-lg 2xl:text-2xl text-gray-800 italic font-normal mb-4 border-b-2 border-orange-200">Authors and
                Affiliations</h3>
            <div onClick={() => toggleAuthorDetails(content.content_id)}>
                {isSelected ? <AuthorAffiliationContainer contentId={content.content_id}/> : <button className="sm:text-base md:text-lg lg:text-xl 2xl:text-2xl bg-neutral-200 drop-shadow-md hover:shadow-xl active:shadow-none rounded-md p-2">Click here to view information about the authors and affiliations for this piece of content</button>}
            </div>
            <div>
                <h3 className="text-lg 2xl:text-2xl text-gray-800 italic font-normal mb-4 mt-4 border-b-2 border-orange-200">Notes</h3>
                {user.signedIn &&
                    <Note contentId={content.content_id}/>
                }
                {!user.signedIn &&
                   <Message message={"You must be signed in to view your notes"}/>
                }
            </div>
        </div>
    )
}

