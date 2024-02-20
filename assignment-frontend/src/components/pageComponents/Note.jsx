import UserContext from "../../helpers/userContext.jsx";
import {useContext, useEffect, useRef, useState} from "react";
import toast, {Toaster} from "react-hot-toast";
import Message from "./Message.jsx";

/**
 * A component to display information about a notes for a specific piece of content, allows the user to add, update and delete notes too
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 * @generated had ChatGPT help with the various fetch requests, error handling and refactoring
 */
export default function Note({contentId}) {
    const user = useContext(UserContext)
    const [contentNote, setContentNote] = useState(null)
    const [error, setError] = useState(null)
    const [loading, setLoading] = useState(null)
    const [currentNote, setCurrentNote] = useState(0)
    const [showEdit, setShowEdit] = useState(false)
    const [showAdd, setShowAdd] = useState(false)
    const editText = useRef(null)
    const [addText, setAddText] = useState('')


    useEffect(() => {
        if (user.signedIn) {
            setLoading(true)
            fetch(`https://w19011575.nuwebspace.co.uk/assignment/api/notes?contentID=${contentId}`,
                {
                    method: 'GET',
                    headers: new Headers({"Authorization": "Bearer " + localStorage.getItem('token')})
                })
                .then(response => {
                        if (response.status === 401) {
                            user.setSignedIn(false)
                            setLoading(false)
                            throw new Error('You have been signed out, please sign in again')
                        }
                        if (response.status === 404) {
                            setLoading(false)
                            setError('There are no notes for this content')
                            throw new Error("There are no notes for this content")
                        }
                        return response.json()
                    }
                )
                .then(data => {
                    setLoading(false)
                    setContentNote(data)
                })
                .catch(() => {
                        setLoading(false)
                        setError("There was an error loading notes, please try again later")
                    }
                )
        } else {
            setContentNote(null)
            setCurrentNote(0)
        }
    }, [user.signedIn])

    const numberOfNotes = contentNote?.data?.length

    const deleteNote = (noteId, noteIndex) => {
        fetch(`https://w19011575.nuwebspace.co.uk/assignment/api/notes?noteId=${noteId}`, {
            method: 'DELETE',
            headers: new Headers({"Authorization": "Bearer " + localStorage.getItem('token')})
        })
            .then(response => {
                if (response.status === 401) {
                    user.setSignedIn(false)
                    setLoading(false)
                    throw new Error('Not authorised')
                }
                if (response.status === 404) {
                    throw new Error("Note not found for given ID: " + noteId)
                }
                if (response.status !== 204) {
                    throw new Error("Something went wrong when trying to delete note: " + noteId)
                }

                if (Array.isArray(contentNote.data)) {
                    const updatedNotes = [...contentNote.data]
                    updatedNotes.splice(noteIndex, 1)
                    setContentNote({...contentNote, data: updatedNotes})
                    setCurrentNote(0)
                    toast.success('Note deleted')
                } else {
                    throw new Error("contentNote is not an array")
                }
            })
            .catch(() => {
                setLoading(false)
                setError("There was an error deleting the note, try again later")
            })
    }

    const updateNote = (noteId, noteIndex) => {
        const text = editText.current.value

        fetch(`https://w19011575.nuwebspace.co.uk/assignment/api/notes?noteId=${noteId}&note=${text}`, {
            method: 'PUT',
            headers: new Headers({"Authorization": "Bearer " + localStorage.getItem('token')})
        })
            .then(response => {
                if (response.status === 401) {
                    user.setSignedIn(false)
                    setLoading(false)
                    throw new Error('Not authorised')
                }
                if (response.status === 404) {
                    throw new Error("Note not found for given ID: " + noteId)
                }
                if (response.status !== 200) {
                    throw new Error("Something went wrong when trying to update note: " + noteId)
                }

                if (Array.isArray(contentNote.data)) {
                    const updatedNotes = [...contentNote.data]

                    if (noteIndex >= 0 && noteIndex < updatedNotes.length) {
                        updatedNotes[noteIndex] = {
                            ...updatedNotes[noteIndex],
                            note_text: text,
                            updated_at: new Date().toISOString().replace('T', ' ').substring(0, 19)
                        }

                        setContentNote({ ...contentNote, data: updatedNotes })
                        setCurrentNote(noteIndex)
                        setShowAdd(false)
                        setShowEdit(false)
                        toast.success('Note updated')
                    } else {
                        throw new Error("Invalid note index: " + noteIndex)
                    }
                } else {
                    throw new Error("contentNote is not an array")
                }
            })
            .catch(() => {
                setLoading(false)
                setError("There was an error updating the note, try again later")
            })
    }

    const addNote = (contentId) => {
        fetch(`https://w19011575.nuwebspace.co.uk/assignment/api/notes?contentID=${contentId}&note=${addText}`, {
            method: 'POST',
            headers: new Headers({"Authorization": "Bearer " + localStorage.getItem('token')})
        })
            .then(response => {
                if (response.status === 401) {
                    user.setSignedIn(false)
                    setLoading(false)
                    throw new Error('Not authorised')
                }
                if (response.status !== 201) {
                    throw new Error("Something went wrong when trying to add a note for content: " + contentId)
                }
                return response.json()
            }).then((json) => {
                if (Array.isArray(contentNote.data)) {
                    const updatedNotes = [...contentNote.data]
                    updatedNotes.push(json.data[0])
                    setContentNote({ ...contentNote, data: updatedNotes })
                    setCurrentNote(updatedNotes.length - 1)
                    setShowAdd(false)
                    setShowEdit(false)
                    toast.success('Note added')
                } else {
                    throw new Error("contentNote is not an array")
                }
        })
            .catch(() => {
                setLoading(false)
                setError("There was an error adding the note, please try again later")
            })
    }

    return (
        <>
            <Toaster/>
            <div onClick={() => {
                if (error) {
                    setError(null)
                }
            }} className="drop-shadow-md bg-neutral-200 rounded-xl">
                {user.signedIn && error && <Message message={error}/>}
                {loading && <Message message={"Loading notes for this content"}/>}
                {!loading && !error && contentNote && contentNote.data.length === 0 &&
                    <Message message={"You have no notes for this piece of content"}/>}
                {!loading && user.signedIn && !error && contentNote && contentNote.data.length > 0 && (
                    <div className="p-2 m-2">
                        <div className="flex mb-2 justify-between">
                            <h4 className="w-full text-lg italic border-b-2 border-neutral-400 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl">Note {currentNote + 1}/{numberOfNotes}</h4>
                            <span
                                className="border-b-2 border-neutral-400 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl pr-4"
                                onClick={() => {setShowEdit(!showEdit)}}>✏️</span>
                            <span
                                className="border-b-2 border-neutral-400 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl"
                                onClick={() => deleteNote(contentNote.data[currentNote].note_id, currentNote)}>❌</span>
                        </div>
                        <p className="text-neutral-700 mt-2 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl">{contentNote.data[currentNote].note_text}</p>
                        {numberOfNotes > 1 && (
                            <ul className="flex flex-row justify-center">
                                {currentNote > 0 &&
                                    <li onClick={() => {
                                        setCurrentNote(currentNote - 1)
                                        setShowEdit(false)
                                    }}
                                        className="rounded drop-shadow-md hover:shadow-xl bg-orange-200 p-3 m-3">Prev</li>}
                                {currentNote < numberOfNotes - 1 &&
                                    <li onClick={() => {
                                        setCurrentNote(currentNote + 1)
                                        setShowEdit(false)
                                    }}
                                        className="rounded drop-shadow-md hover:shadow-xl bg-orange-200 p-3 m-3">Next</li>}
                            </ul>
                        )
                        }
                        {showEdit && (
                            <div className="mt-5 flex flex-col">
                                                    <textarea
                                                        ref={editText}
                                                        placeholder="Edit note"
                                                        rows="5"
                                                        cols="50"
                                                        maxLength="250"
                                                        className="p-2"
                                                        defaultValue={contentNote.data[currentNote].note_text}
                                                    />

                                <input
                                    type="submit"
                                    value="Save edit"
                                    className="w-full my-2 bg-slate-700 text-white rounded-md"
                                    onClick={() => updateNote(contentNote.data[currentNote].note_id, currentNote)}
                                />
                            </div>
                        )}
                    </div>
                )}
            </div>
            {user.signedIn && (
                <div className="flex flex-col justify-center">
                    {showAdd && (
                        <div className="flex flex-col mt-5">
                                                    <textarea
                                                        placeholder="Add a new note"
                                                        rows="5"
                                                        cols="50"
                                                        maxLength="250"
                                                        className="p-2"
                                                        value={addText}
                                                        onChange={(e) => setAddText(e.target.value)}
                                                    />

                            <input
                                type="submit"
                                value="Add new note"
                                className="w-full my-2 bg-slate-700 text-white rounded-md"
                                onClick={() => addNote(contentId)}
                            />
                        </div>
                    )}
                    <button className={`m-2 mt-5 p-2 rounded drop-shadow-md hover:shadow-md ${!showAdd ? "bg-green-300" : "bg-red-300"}`} onClick={() => setShowAdd(!showAdd)}>{!showAdd ? "Add a new note" : "Close add note"}</button>
                </div>
            )}
        </>
    )
}