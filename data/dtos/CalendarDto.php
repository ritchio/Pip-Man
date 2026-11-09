<?php
class CalendarDto {
    public function __construct(
        public int $id = 0,
        public string $name = "",
        public string $description = "",
        public string $start_date = "",
        public string $end_date = "",
        public int $created_by = 0,
        public int $status = 0
    ) {}

    public static function fromArray(array $data): self {
        return new self(
            id: $data["id"] ?? 0,
            name: $data["name"] ?? "",
            description: $data["description"] ?? "",
            start_date: $data["start_date"] ?? "",
            end_date: $data["end_date"] ?? "",
            created_by: $data["created_by"] ?? $_SESSION["user_id"],
            status: $data["status"] ?? 0
        );
    }

    public function toArray(): array {
        return [
            "id" => $this->id > 0 ? $this->id : null,
            "name" => $this->name,
            "description" => $this->description,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "created_by" => $this->created_by > 0 ? $this->created_by : $_SESSION["user_id"],
            "status" => $this->status
        ];
    }
}