$(function () {
    function initDataTable() {
        return $("#ticketsTable").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: listUrl,
                data: function (d) {
                    d.client = $("#client").val();
                    d.agent = $("#agent").val();
                    d.status = $("#searchStatus").val();
                },
            },
            columns: [
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
                { data: "created_at", name: "created_at" },
                { data: "no_ticket", name: "no_ticket" },
                { data: "location_name", name: "location_name" },
                { data: "kendala", name: "kendala" },
                {
                    data: "detail_kendala",
                    name: "detail_kendala",
                },
                { data: "agent_name", name: "agent_name" },
                { data: "status_raw", name: "status" },
                { data: "notes_raw", name: "notes_raw" },
                { data: "work_time", name: "work_time" },
            ],
            columnDefs: [
                {
                    targets: [1, 2, 3, 4, 5],
                    render: function (data, type) {
                        if (type === "display" && typeof data === "string") {
                            return data.toUpperCase();
                        }
                        return data;
                    },
                },
                {
                    targets: [4, 5], // SUBJECT & DETAIL
                    createdCell: function (td, cellData, rowData, row, col) {
                        td.style.maxWidth = "100px";
                        td.style.whiteSpace = "nowrap";
                        td.style.overflow = "hidden";
                        td.style.textOverflow = "ellipsis";
                        td.title = cellData;
                    },
                },
                {
                    targets: 6,
                    render: function (data, type, row) {
                        if (row.is_me) {
                            return '<span class="badge bg-info">ME</span>';
                        }
                        return data;
                    },
                },
                {
                    targets: 7,
                    render: function (data, type, row) {
                        if (type !== "display") return row.status_raw;

                        const role = row.user_role;

                        switch (row.status_raw) {
                            case "created":
                                return '<span class="badge bg-secondary">CREATED</span>';

                            case "onprocess":
                                return '<span class="badge bg-warning">ONPROCESS</span>';

                            case "standby":
                                if (role === "client") {
                                    return '<span class="badge bg-warning">ONPROCESS</span>';
                                }
                                return '<span class="badge bg-warning">STANDBY</span>';

                            case "pending":
                                return '<span class="badge bg-danger">PENDING</span>';

                            case "resolved":
                                return '<span class="badge bg-primary">RESOLVED</span>';

                            case "finished":
                                return '<span class="badge bg-success">FINISHED</span>';

                            default:
                                return (
                                    '<span class="badge bg-danger">' +
                                    row.status_raw +
                                    "</span>"
                                );
                        }
                    },
                },
                {
                    targets: 8, // kolom NOTE
                    render: function (data, type, row) {
                        if (row.need_approval === "ya" && !row.approved) {
                            return '<span class="badge bg-secondary">WAITING FOR APPROVAL</span>';
                        }

                        if (
                            row.need_approval === "ya" &&
                            ["approved", "rejected"].includes(row.approved)
                        ) {
                            return (
                                '<span class="badge bg-dark">' +
                                row.approved.toUpperCase() +
                                "</span>"
                            );
                        }

                        if (
                            row.assigned === "ya" &&
                            ["created", "pending"].includes(row.status_raw)
                        ) {
                            return '<span class="badge bg-dark">DIRECT ASSIGN</span>';
                        }

                        if (
                            row.assigned === "tidak" &&
                            row.status_raw === "pending"
                        ) {
                            return (
                                row.last_pending_reason ?? ""
                            ).toUpperCase();
                        }

                        if (
                            row.is_queue === "ya" &&
                            ["created", "pending"].includes(row.status_raw)
                        ) {
                            let text = "IN QUEUE";
                            if (row.sub_divisi_agent) {
                                text +=
                                    " | " + row.sub_divisi_agent.toUpperCase();
                            }
                            return (
                                '<span class="badge bg-dark">' +
                                text +
                                "</span>"
                            );
                        }

                        // 6. Not in queue (client)
                        if (
                            row.is_queue === "tidak" &&
                            row.status_raw === "created" &&
                            row.role == 1
                        ) {
                            return '<span class="badge bg-secondary">NOT IN QUEUE</span>';
                        }

                        return "";
                    },
                },
            ],
            order: [
                [7, "asc"],
                [6, "asc"],
                [1, "asc"],
            ],
        });
    }

    let table = initDataTable();

    $("#reloadBtn").on("click", function (e) {
        table.ajax.reload();
    });

    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
